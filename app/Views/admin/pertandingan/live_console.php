<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="bg-gray-800 rounded-xl p-6 mb-6 text-white shadow-2xl">
    <div class="flex justify-between items-center text-center">
        <div class="w-1/3">
            <img src="/img/<?= $match['home_logo'] ?>" class="w-20 h-20 mx-auto mb-2 object-contain bg-white rounded-full p-1"
                 onerror="this.src='https://ui-avatars.com/api/?name=<?= $match['home_team'] ?>'">
            <h2 class="text-2xl font-bold"><?= $match['home_team'] ?></h2>
        </div>

        <div class="w-1/3">
            <div class="text-6xl font-mono font-bold tracking-widest mb-2">
                <?= $match['skor_a'] ?? 0 ?> - <?= $match['skor_b'] ?? 0 ?>
            </div>
            
            <div class="flex flex-col items-center gap-2">
                <?php if($match['status'] == 'berlangsung'): ?>
                    <span class="bg-red-600 px-4 py-1 rounded-full text-sm animate-pulse">LIVE ●</span>
                    <span id="liveTimer" class="font-mono text-xl font-bold text-yellow-400" 
                          data-elapsed="<?= $elapsed_seconds ?>">00:00</span>
                <?php elseif($match['status'] == 'halftime'): ?>
                    <span class="bg-yellow-600 px-4 py-1 rounded-full text-sm font-bold">HALF TIME (HT)</span>
                <?php elseif($match['status'] == 'selesai'): ?>
                    <span class="bg-green-600 px-4 py-1 rounded-full text-sm font-bold">FULL TIME (FT)</span>
                <?php else: ?>
                    <span class="bg-gray-500 px-4 py-1 rounded-full text-sm">BELUM MULAI</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="w-1/3">
            <img src="/img/<?= $match['away_logo'] ?>" class="w-20 h-20 mx-auto mb-2 object-contain bg-white rounded-full p-1"
                 onerror="this.src='https://ui-avatars.com/api/?name=<?= $match['away_team'] ?>'">
            <h2 class="text-2xl font-bold"><?= $match['away_team'] ?></h2>
        </div>
    </div>
</div>

<?php if($match['status'] != 'selesai'): ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-600">
        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2"><i class="fas fa-stopwatch"></i> Kontrol Waktu</h3>
        
        <div class="flex flex-col gap-3">
            <?php if($match['status'] == 'belum'): ?>
                <a href="/dashboard/live/start/<?= $match['id'] ?>" onclick="return confirm('Mulai Pertandingan?')" 
                   class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg text-center text-xl shadow-lg transition transform hover:scale-105">
                   <i class="fas fa-play mr-2"></i> KICKOFF
                </a>
            
            <?php elseif($match['status'] == 'berlangsung'): ?> 
                
                <?php if(!$isBabak2): ?>
                    <a href="/dashboard/live/halftime/<?= $match['id'] ?>" onclick="return confirm('Akhiri Babak 1?')" 
                       class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 rounded-lg text-center text-xl shadow-lg">
                       <i class="fas fa-pause mr-2"></i> HT (Babak 1 Selesai)
                    </a>
                <?php else: ?>
                    <a href="/dashboard/live/end/<?= $match['id'] ?>" onclick="return confirm('Peluit Panjang (FT)?')" 
                       class="w-full bg-gray-800 hover:bg-black text-white font-bold py-4 rounded-lg text-center text-xl shadow-lg">
                       <i class="fas fa-flag-checkered mr-2"></i> FULL TIME (Selesai)
                    </a>
                <?php endif; ?>

            <?php elseif($match['status'] == 'halftime'): ?>
                <a href="/dashboard/live/second_half/<?= $match['id'] ?>" onclick="return confirm('Mulai Babak 2?')" 
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg text-center text-xl shadow-lg">
                   <i class="fas fa-play mr-2"></i> Mulai Babak 2
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-green-600">
        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2"><i class="fas fa-bolt"></i> Input Kejadian</h3>
        
        <?php if($match['status'] == 'berlangsung'): ?>
        <form action="/dashboard/live/event" method="post">
            <input type="hidden" name="id_jadwal" value="<?= $match['id'] ?>">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-xs text-gray-500 font-bold uppercase">Kejadian</label>
                    <select name="jenis" class="w-full border p-2 rounded bg-gray-50 font-bold" required>
                        <option value="goal">⚽ GOL</option>
                        <option value="yellow_card">🟨 Kartu Kuning</option>
                        <option value="red_card">🟥 Kartu Merah</option>
                        <option value="penalty_goal">🥅 Gol Penalti</option>
                        <option value="penalty_miss">❌ Gagal Penalti</option>
                        <option value="own_goal">🔄 Gol Bunuh Diri</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-bold uppercase">Menit (Auto)</label>
                    <input type="number" name="menit" id="inputMenit" placeholder="Menit" class="w-full border p-2 rounded bg-gray-100 font-mono font-bold" readonly required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-xs text-gray-500 font-bold uppercase">Tim Pelaku</label>
                    <select name="id_tim" id="selectTim" class="w-full border p-2 rounded" required onchange="filterPemain()">
                        <option value="" disabled selected>-- Pilih Tim --</option>
                        <option value="<?= $match['home_id'] ?>"><?= $match['home_team'] ?></option>
                        <option value="<?= $match['away_id'] ?>"><?= $match['away_team'] ?></option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1 italic">*Jika Gol Bunuh Diri, pilih Tim yang melakukan kesalahan.</p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 font-bold uppercase">Pemain</label>
                    <select name="id_pemain" id="selectPemain" class="w-full border p-2 rounded">
                        <option value="">-- Pilih Pemain --</option>
                        <optgroup label="<?= $match['home_team'] ?>" class="group-home" data-team-id="<?= $match['home_id'] ?>">
                            <?php foreach($pemain_home as $ph): ?>
                                <option value="<?= $ph['id'] ?>"><?= $ph['nama_pemain'] ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="<?= $match['away_team'] ?>" class="group-away" data-team-id="<?= $match['away_id'] ?>">
                            <?php foreach($pemain_away as $pa): ?>
                                <option value="<?= $pa['id'] ?>"><?= $pa['nama_pemain'] ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded shadow transition transform active:scale-95">
                Kirim Update
            </button>
        </form>
        <?php else: ?>
            <div class="text-center text-gray-400 py-10 bg-gray-50 rounded border border-dashed border-gray-300">
                <i class="fas fa-lock text-2xl mb-2"></i>
                <p>Input terkunci. <br>Klik "KICKOFF" atau "Mulai Babak 2" dulu.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">📜 Riwayat Pertandingan (Timeline)</h3>
    <?php if(empty($events)): ?>
        <p class="text-gray-400 italic">Belum ada kejadian.</p>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach($events as $ev): ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded border-l-4 
                <?= ($ev['jenis_event'] == 'goal' || $ev['jenis_event'] == 'penalty_goal') ? 'border-green-500 bg-green-50' : 
                   (($ev['jenis_event'] == 'red_card') ? 'border-red-500 bg-red-50' : 'border-gray-400') ?>">
                
                <div class="flex items-center gap-3">
                    <span class="font-bold text-gray-600">'<?= $ev['menit'] ?></span>
                    <?php if($ev['jenis_event'] == 'goal' || $ev['jenis_event'] == 'penalty_goal'): ?>
                        <span class="text-xl">⚽</span>
                    <?php elseif($ev['jenis_event'] == 'own_goal'): ?>
                        <span class="text-xl text-red-500">🔄</span>
                    <?php elseif($ev['jenis_event'] == 'yellow_card'): ?>
                        <span class="text-xl">🟨</span>
                    <?php elseif($ev['jenis_event'] == 'red_card'): ?>
                        <span class="text-xl">🟥</span>
                    <?php elseif($ev['jenis_event'] == 'halftime'): ?>
                        <span class="font-bold bg-yellow-200 px-2 rounded text-xs text-yellow-800">HT</span>
                    <?php elseif($ev['jenis_event'] == 'fulltime'): ?>
                        <span class="font-bold bg-gray-800 text-white px-2 rounded text-xs">FT</span>
                    <?php elseif($ev['jenis_event'] == 'kickoff' || $ev['jenis_event'] == 'second_half'): ?>
                        <span class="text-xl">📢</span>
                    <?php endif; ?>

                    <div>
                        <span class="font-semibold uppercase text-sm text-gray-800">
                            <?= str_replace('_', ' ', $ev['jenis_event']) ?>
                        </span>
                        <?php if($ev['nama_pemain']): ?>
                            <span class="text-gray-600 text-sm font-bold">- <?= $ev['nama_pemain'] ?></span>
                        <?php endif; ?>
                        <?php if($ev['keterangan']): ?>
                            <span class="text-gray-500 text-xs italic ml-2">(<?= $ev['keterangan'] ?>)</span>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="/dashboard/live/delete_event/<?= $ev['id'] ?>/<?= $match['id'] ?>" 
                   onclick="return confirm('Hapus/Batalkan kejadian ini? Skor akan dikoreksi otomatis.')"
                   class="text-red-400 hover:text-red-600 text-xs underline">
                   Batalkan (VAR)
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function filterPemain() {
        const timId = document.getElementById('selectTim').value;
        const selectPemain = document.getElementById('selectPemain');
        selectPemain.value = "";
        const groups = selectPemain.querySelectorAll('optgroup');
        groups.forEach(group => {
            if (group.getAttribute('data-team-id') == timId) {
                group.style.display = "";
                group.disabled = false;
            } else {
                group.style.display = "none";
                group.disabled = true;
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('selectTim').value) filterPemain();
        startLiveTimer();
    });

    function startLiveTimer() {
        const timerElement = document.getElementById('liveTimer');
        const inputMenit = document.getElementById('inputMenit');
        
        if (!timerElement) return;

        const initialElapsed = parseInt(timerElement.getAttribute('data-elapsed')); 
        const startTimeLocal = new Date().getTime(); 

        setInterval(() => {
            const now = new Date().getTime();
            const diffSecsTotal = initialElapsed + Math.floor((now - startTimeLocal) / 1000);
            
            let diffMins = Math.floor(diffSecsTotal / 60); 
            let diffSecs = diffSecsTotal % 60;

            if (diffMins < 0) diffMins = 0;

            timerElement.innerText = diffMins + ":" + (diffSecs < 10 ? "0" + diffSecs : diffSecs);

            if (inputMenit) {
                inputMenit.value = diffMins + 1;
            }

        }, 1000);
    }
</script>

<?= $this->endSection() ?>