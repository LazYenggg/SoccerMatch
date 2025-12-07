<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Jadwal</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-sm" role="alert">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <form action="/dashboard/jadwal/update/<?= $jadwal['id'] ?>" method="post">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Tim Home</label>
                <select name="home" id="selectHome" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300 bg-gray-50" required onchange="updateLogic()">
                    <?php foreach($tim as $t): ?>
                        <option value="<?= $t['id'] ?>" 
                                data-liga="<?= $t['id_liga'] ?>" 
                                data-stadion="<?= $t['stadion'] ?>"
                                <?= ($t['id'] == $jadwal['home']) ? 'selected' : '' ?>>
                            <?= $t['nama_tim'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Tim Away</label>
                <select name="away" id="selectAway" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-red-300">
                    <?php foreach($tim as $t): ?>
                        <option value="<?= $t['id'] ?>" 
                                data-liga="<?= $t['id_liga'] ?>"
                                <?= ($t['id'] == $jadwal['away']) ? 'selected' : '' ?>>
                            <?= $t['nama_tim'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Stadion (Locked)</label>
            <input type="text" name="venue" id="inputVenue" value="<?= $jadwal['venue'] ?>" class="w-full border rounded px-3 py-2 bg-gray-200 text-gray-600 cursor-not-allowed" readonly>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal & Waktu</label>
            <input type="datetime-local" name="tanggal" value="<?= date('Y-m-d\TH:i', strtotime($jadwal['tanggal'])) ?>" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="/dashboard/jadwal" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">Batal</a>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">Update Jadwal</button>
        </div>

    </form>
</div>

<script>
    function updateLogic() {
        const selectHome = document.getElementById('selectHome');
        const selectAway = document.getElementById('selectAway');
        const inputVenue = document.getElementById('inputVenue');

        const selectedOption = selectHome.options[selectHome.selectedIndex];
        const ligaID = selectedOption.getAttribute('data-liga');
        const stadionName = selectedOption.getAttribute('data-stadion');

        // Auto Venue
        if (stadionName) inputVenue.value = stadionName;

        // Filter Away (Sembunyikan tim beda liga)
        // Note: Di Edit mode kita tidak disable selectAway di awal, tapi kita filter isinya
        for (let i = 0; i < selectAway.options.length; i++) {
            const option = selectAway.options[i];
            const optionLiga = option.getAttribute('data-liga');
            
            // Tampilkan jika satu liga ATAU itu adalah opsi yang sedang terpilih (biar data lama gak ilang)
            if (optionLiga == ligaID || option.selected) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        }
    }

    // Jalankan saat load halaman agar filter langsung aktif
    document.addEventListener("DOMContentLoaded", function() {
        updateLogic();
    });
</script>

<?= $this->endSection() ?>