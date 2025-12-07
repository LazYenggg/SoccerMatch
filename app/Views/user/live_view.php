<?= $this->extend('layout/main') ?>

<?= $this->section('isi_halaman') ?>

<div class="max-w-4xl mx-auto mt-6" data-aos="fade-up">

    <div class="bg-gradient-to-r from-gray-900 to-blue-900 rounded-2xl shadow-2xl text-white overflow-hidden relative">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
        
        <div class="relative p-8 text-center">
            <div class="mb-6 flex justify-between items-center text-gray-400 text-sm font-bold uppercase tracking-widest">
                <span>SoccerMatch Live</span>
                <span id="matchStatus"><?= strtoupper($match['status'] ?? 'BELUM') ?></span>
            </div>

            <div class="flex items-center justify-between">
                <div class="w-1/3 flex flex-col items-center">
                    <img src="/img/<?= $match['home_logo'] ?>" class="w-20 h-20 md:w-32 md:h-32 object-contain bg-white rounded-full p-2 shadow-lg mb-4 animate-float">
                    <h2 class="text-xl md:text-3xl font-bold leading-tight"><?= $match['home_team'] ?></h2>
                </div>

                <div class="w-1/3 flex flex-col items-center">
                    <div class="text-6xl md:text-8xl font-mono font-bold tracking-tighter flex items-center gap-4">
                        <span id="scoreHome" class="transition-all duration-500"><?= $match['skor_a'] ?? 0 ?></span>
                        <span class="text-gray-500 text-4xl">:</span>
                        <span id="scoreAway" class="transition-all duration-500"><?= $match['skor_b'] ?? 0 ?></span>
                    </div>
                    
                    <div id="gameTimer" class="mt-4 bg-red-600 px-4 py-1 rounded-full font-mono text-lg font-bold shadow-lg animate-pulse hidden">
                        00:00
                    </div>
                </div>

                <div class="w-1/3 flex flex-col items-center">
                    <img src="/img/<?= $match['away_logo'] ?>" class="w-20 h-20 md:w-32 md:h-32 object-contain bg-white rounded-full p-2 shadow-lg mb-4 animate-float">
                    <h2 class="text-xl md:text-3xl font-bold leading-tight"><?= $match['away_team'] ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-lg p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <i class="fas fa-history text-blue-600"></i> Live Commentary
        </h3>
        
        <div id="timelineContainer" class="space-y-4 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
            <p class="text-center text-gray-400 italic" id="emptyState">Menunggu update pertandingan...</p>
        </div>
    </div>

</div>

<style>
    @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
    .animate-float { animation: float 6s ease-in-out infinite; }
    /* Animasi Skor Berubah */
    .score-pop { transform: scale(1.5); color: #fbbf24; } 
</style>

<?= $this->endSection() ?>

<?= $this->section('script_bawah') ?>
<script>
    const matchId = <?= $match['id'] ?>;
    let currentElapsed = 0;
    let isLive = false;

    // VARIABLE PELACAK (STATE)
    let lastEventCount = -1; // -1 artinya baru load pertama
    let lastStatus = "";     // Melacak perubahan status HT/FT

    // Fungsi Utama: Ambil Data dari Server
    async function fetchLiveData() {
        try {
            const response = await fetch(`/api/live/${matchId}`);
            const data = await response.json();
            
            if(data.status === 200) {
                updateUI(data.match);
                updateTimeline(data.timeline);
                
                // JALANKAN CEK NOTIFIKASI
                checkNotifications(data.match, data.timeline);
            }
        } catch (error) {
            console.error("Gagal update data:", error);
        }
    }

    // --- LOGIKA NOTIFIKASI PINTAR ---
    function checkNotifications(match, events) {
        // 1. Cek Perubahan Status (HT / FT / Kickoff Babak 2)
        if (lastStatus !== "" && lastStatus !== match.status_str) {
            if (match.status_str === 'HALFTIME') {
                showToast("⏸️ HALF TIME! Babak pertama selesai.", "blue");
            } else if (match.status_str === 'SELESAI') {
                showToast("🏁 FULL TIME! Pertandingan berakhir.", "black");
            } else if (match.status_str === 'BERLANGSUNG' && lastStatus === 'HALFTIME') {
                showToast("📢 KICKOFF! Babak kedua dimulai.", "green");
            }
        }
        lastStatus = match.status_str;

        // 2. Cek Event Baru (Gol / Kartu)
        // Init lastEventCount saat pertama kali load
        if (lastEventCount === -1) {
            lastEventCount = events.length;
            return;
        }

        // Jika jumlah event bertambah, berarti ada kejadian baru
        if (events.length > lastEventCount) {
            // Karena API mengurutkan DESC (Terbaru diatas), event baru ada di index 0
            const newEvent = events[0]; 
            
            // Tampilkan Notifikasi Toast
            showToast(`${newEvent.icon} ${newEvent.text}`, newEvent.color);
            
            // Efek Suara (Opsional - Browser kadang block auto audio)
            // const audio = new Audio('https://freesound.org/data/previews/171/171671_2437358-lq.mp3');
            // audio.play().catch(e => console.log("Audio autoplay blocked"));
        }
        
        // Update jumlah event terakhir
        lastEventCount = events.length;
    }

    // Fungsi Pembantu Tampilkan Toastify Keren
    function showToast(message, colorType) {
        let bg = "linear-gradient(to right, #00b09b, #96c93d)"; // Green (Default)
        
        if (colorType === 'red') bg = "linear-gradient(to right, #ff5f6d, #ffc371)"; // Merah (Kartu Merah/Own Goal)
        if (colorType === 'yellow') bg = "linear-gradient(to right, #f7971e, #ffd200)"; // Kuning (Kartu Kuning)
        if (colorType === 'blue') bg = "linear-gradient(to right, #2193b0, #6dd5ed)"; // Biru (HT)
        if (colorType === 'black') bg = "linear-gradient(to right, #232526, #414345)"; // Hitam (FT)

        Toastify({
            text: message,
            duration: 5000,
            gravity: "top", // Muncul dari ATAS
            position: "right", // Di KANAN
            stopOnFocus: true,
            style: { 
                background: bg, 
                boxShadow: "0 10px 15px -3px rgba(0, 0, 0, 0.1)",
                borderRadius: "8px",
                fontWeight: "bold",
                fontSize: "14px",
                padding: "12px 20px"
            }
        }).showToast();
    }

    // Update Tampilan Utama (Skor & Status)
    function updateUI(match) {
        // Efek Pop pada Skor jika berubah
        const elScoreHome = document.getElementById('scoreHome');
        const elScoreAway = document.getElementById('scoreAway');
        
        if (elScoreHome.innerText != match.skor_home) {
            elScoreHome.innerText = match.skor_home;
            elScoreHome.classList.add('score-pop');
            setTimeout(() => elScoreHome.classList.remove('score-pop'), 500);
        }
        if (elScoreAway.innerText != match.skor_away) {
            elScoreAway.innerText = match.skor_away;
            elScoreAway.classList.add('score-pop');
            setTimeout(() => elScoreAway.classList.remove('score-pop'), 500);
        }
        
        // Update Status Text
        const statusEl = document.getElementById('matchStatus');
        const timerEl = document.getElementById('gameTimer');
        
        statusEl.innerText = match.status_str.replace('_', ' ');

        // Logic Timer Display
        if (match.status_str === 'BERLANGSUNG') {
            isLive = true;
            currentElapsed = match.elapsed; 
            timerEl.classList.remove('hidden');
            statusEl.classList.add('text-red-400');
        } else {
            isLive = false;
            statusEl.classList.remove('text-red-400');
            
            if (match.status_str === 'HALFTIME') {
                timerEl.innerText = "HT";
                timerEl.classList.remove('hidden', 'bg-red-600');
                timerEl.classList.add('bg-yellow-600');
            } else if (match.status_str === 'SELESAI') {
                timerEl.innerText = "FT";
                timerEl.classList.remove('hidden', 'bg-red-600', 'animate-pulse');
                timerEl.classList.add('bg-green-600');
            } else {
                timerEl.classList.add('hidden'); // Belum mulai
            }
        }
    }

    // Update Timeline (List Kejadian)
    function updateTimeline(events) {
        const container = document.getElementById('timelineContainer');
        const emptyState = document.getElementById('emptyState');

        if (events.length > 0) {
            if(emptyState) emptyState.remove();
            
            let html = '';
            events.forEach(ev => {
                let borderClass = 'border-gray-200';
                if(ev.color === 'green') borderClass = 'border-green-500 bg-green-50';
                if(ev.color === 'red') borderClass = 'border-red-500 bg-red-50';
                if(ev.color === 'yellow') borderClass = 'border-yellow-500 bg-yellow-50';

                html += `
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active animate-fade-in-down">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-slate-200 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 text-xl">
                        ${ev.icon}
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-xl border ${borderClass} shadow-sm transition hover:shadow-md">
                        <div class="flex justify-between items-center mb-1">
                            <span class="font-bold text-gray-700">${ev.text}</span>
                            <span class="font-mono text-xs font-bold bg-gray-800 text-white px-2 py-1 rounded">'${ev.menit}</span>
                        </div>
                        ${ev.ket ? `<p class="text-xs text-gray-500 italic">${ev.ket}</p>` : ''}
                    </div>
                </div>`;
            });
            // Cek biar ga render ulang kalau html sama (biar ga kedip)
            if (container.innerHTML !== html) {
                container.innerHTML = html;
            }
        }
    }

    // Timer Jalan Sendiri di Client
    setInterval(() => {
        if (isLive) {
            currentElapsed++; 
            let mins = Math.floor(currentElapsed / 60);
            let secs = currentElapsed % 60;
            document.getElementById('gameTimer').innerText = mins + ":" + (secs < 10 ? "0" + secs : secs);
        }
    }, 1000);

    // Polling ke Server setiap 3 detik (Dipercepat biar lebih realtime)
    fetchLiveData(); 
    setInterval(fetchLiveData, 3000); 

</script>
<?= $this->endSection() ?>