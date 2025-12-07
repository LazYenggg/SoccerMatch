<?= $this->extend('layout/main') ?>

<?= $this->section('isi_halaman') ?>

<div class="mb-10 text-center" data-aos="fade-down" data-aos-duration="1000">
    <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-800 to-blue-500 mb-2">
        Jadwal Pertandingan
    </h1>
    <p class="text-gray-500">Pantau jadwal Kickoff 5 Liga Top Eropa di sini.</p>
</div>

<div class="bg-white rounded-xl shadow-2xl overflow-hidden p-6 border border-gray-100" data-aos="fade-up" data-aos-delay="200">
    
    <div class="overflow-x-auto">
        <table id="tabelJadwal" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-50 text-blue-900 uppercase text-xs font-bold tracking-wider text-center">
                    <th class="p-4 text-left rounded-tl-lg">Waktu (WIB)</th>
                    <th class="p-4">Home Team</th>
                    <th class="p-4">Skor</th>
                    <th class="p-4">Away Team</th>
                    <th class="p-4">Stadion</th>
                    <th class="p-4 rounded-tr-lg">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                <?php foreach($jadwal as $j): ?>
                <tr class="border-b border-gray-100 hover:bg-blue-50 transition duration-300">
                    
                    <td class="p-4 font-semibold text-blue-600">
                        <?= date('d M Y', strtotime($j['tanggal'])) ?><br>
                        <span class="text-gray-400 text-xs"><?= date('H:i', strtotime($j['tanggal'])) ?> WIB</span>
                    </td>

                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <span class="font-bold text-base hidden md:block"><?= $j['tim_home'] ?></span>
                            
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden shadow-sm ring-2 ring-white">
                                <img 
                                    src="/img/<?= $j['logo_home'] ?>" 
                                    alt="<?= $j['tim_home'] ?>"
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($j['tim_home']) ?>&background=random&color=fff&bold=true';"
                                >
                            </div>
                        </div>
                    </td>

                    <td class="p-4 text-center">
                        <div class="bg-gray-800 text-white py-1 px-3 rounded-lg inline-block font-mono font-bold tracking-widest shadow-md">
                            <?= $j['skor_a'] ?? '0' ?> - <?= $j['skor_b'] ?? '0' ?>
                        </div>
                    </td>

                    <td class="p-4 text-left">
                        <div class="flex items-center justify-start gap-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden shadow-sm ring-2 ring-white">
                                <img 
                                    src="/img/<?= $j['logo_away'] ?>" 
                                    alt="<?= $j['tim_away'] ?>"
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($j['tim_away']) ?>&background=random&color=fff&bold=true';"
                                >
                            </div>
                            <span class="font-bold text-base hidden md:block"><?= $j['tim_away'] ?></span>
                        </div>
                    </td>

                    <td class="p-4 text-center text-xs text-gray-500">
                        <i class="fas fa-map-marker-alt"></i> <?= $j['venue'] ?>
                    </td>

                    <td class="p-4 text-center">
                        <?php if($j['status'] == 'berlangsung' || $j['status'] == 'live'): ?>
                            <a href="/match/<?= $j['id'] ?>" class="inline-block bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-1.5 rounded-full animate-pulse shadow-md transition transform hover:scale-105">
                                LIVE 📺
                            </a>
                        <?php elseif($j['status'] == 'halftime'): ?>
                            <a href="/match/<?= $j['id'] ?>" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-md transition transform hover:scale-105">
                                HT (Detail)
                            </a>
                        <?php elseif($j['status'] == 'selesai' || $j['status'] == 'finished'): ?>
                            <a href="/match/<?= $j['id'] ?>" class="inline-block bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-md transition transform hover:scale-105">
                                FT (Detail)
                            </a>
                        <?php else: ?>
                            <a href="/match/<?= $j['id'] ?>" class="inline-block bg-blue-100 hover:bg-blue-200 text-blue-600 text-xs font-bold px-4 py-1.5 rounded-full border border-blue-200 transition transform hover:scale-105">
                                Lihat
                            </a>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('script_bawah') ?>
<script>
    $(document).ready(function() {
        // A. Aktifkan DataTables
        $('#tabelJadwal').DataTable({
            responsive: true,
            language: { 
                search: "🔍 Cari Tim:", 
                paginate: { next: "Next", previous: "Prev" },
                zeroRecords: "Jadwal tidak ditemukan",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ pertandingan"
            }
        });

        // B. Cek Otomatis jika ada yang LIVE -> Muncul Notifikasi Pojok
        // Kita cek dari tombol LIVE yang ada di tabel
        if ($("a:contains('LIVE')").length > 0) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: 'warning', 
                title: '🔥 Ada Pertandingan LIVE saat ini! Klik tombol LIVE untuk menonton.'
            });
        }
    });
</script>
<?= $this->endSection() ?>