<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Tambah Jadwal Resmi</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-sm rounded shadow-sm">
            <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="/dashboard/jadwal/simpan" method="post">
        
        <div class="mb-6 bg-blue-50 p-4 rounded border border-blue-200">
            <label class="block text-blue-800 text-sm font-bold mb-2">Langkah 1: Pilih Liga</label>
            <select id="selectLiga" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" onchange="filterByLiga()">
                <option value="">-- Pilih Liga --</option>
                <?php foreach($ligas as $l): ?>
                    <option value="<?= $l['liga'] ?>"><?= $l['liga'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Tim Home (Tuan Rumah)</label>
                <select name="home" id="selectHome" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300 bg-gray-50 disabled:bg-gray-200" required disabled onchange="updateVenueAndAway()">
                    <option value="" data-liga="" data-stadion="">-- Pilih Liga Dulu --</option>
                    <?php foreach($tim as $t): ?>
                        <option value="<?= $t['id'] ?>" 
                                data-liga="<?= $t['liga'] ?>" 
                                data-stadion="<?= $t['stadion'] ?>">
                            <?= $t['nama_tim'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Tim Away (Tamu)</label>
                <select name="away" id="selectAway" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-red-300 disabled:bg-gray-200" required disabled>
                    <option value="">-- Pilih Home Dulu --</option>
                    <?php foreach($tim as $t): ?>
                        <option value="<?= $t['id'] ?>" data-liga="<?= $t['liga'] ?>">
                            <?= $t['nama_tim'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Stadion / Venue</label>
            <div class="relative">
                <input type="text" name="venue" id="inputVenue" class="w-full border rounded px-3 py-2 bg-gray-200 text-gray-600 cursor-not-allowed font-bold" readonly placeholder="Otomatis..." required>
                <span class="absolute right-3 top-2 text-gray-400"><i class="fas fa-lock"></i></span>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal & Waktu Kickoff</label>
            <input type="datetime-local" name="tanggal" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="/dashboard/jadwal" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">Simpan Jadwal</button>
        </div>

    </form>
</div>

<script>
    // 1. FILTER HOME BERDASARKAN LIGA
    function filterByLiga() {
        const ligaDipilih = document.getElementById('selectLiga').value;
        const selectHome = document.getElementById('selectHome');
        const selectAway = document.getElementById('selectAway');
        const inputVenue = document.getElementById('inputVenue');

        // Reset
        selectHome.value = "";
        selectAway.value = "";
        selectAway.disabled = true;
        inputVenue.value = "";

        if (ligaDipilih) {
            selectHome.disabled = false;
            // Loop opsi Home
            for (let i = 0; i < selectHome.options.length; i++) {
                const option = selectHome.options[i];
                const timLiga = option.getAttribute('data-liga');
                
                // Tampilkan jika liganya sama, atau opsi default (value kosong)
                if (option.value === "" || timLiga == ligaDipilih) {
                    option.style.display = "";
                } else {
                    option.style.display = "none";
                }
            }
        } else {
            selectHome.disabled = true;
        }
    }

    // 2. FILTER AWAY & AUTO VENUE
    function updateVenueAndAway() {
        const selectHome = document.getElementById('selectHome');
        const selectAway = document.getElementById('selectAway');
        const inputVenue = document.getElementById('inputVenue');
        
        const selectedOption = selectHome.options[selectHome.selectedIndex];
        const ligaID = selectedOption.getAttribute('data-liga');
        const stadionName = selectedOption.getAttribute('data-stadion');

        // Isi Venue
        inputVenue.value = stadionName || "";

        // Aktifkan Away & Filter
        if (selectHome.value) {
            selectAway.disabled = false;
            
            for (let i = 0; i < selectAway.options.length; i++) {
                const option = selectAway.options[i];
                const timLiga = option.getAttribute('data-liga');
                const timID = option.value;

                // Tampilkan jika: Satu Liga DAN Bukan Tim Home itu sendiri
                if (option.value === "" || (timLiga == ligaID && timID != selectHome.value)) {
                    option.style.display = "";
                } else {
                    option.style.display = "none";
                }
            }
        } else {
            selectAway.disabled = true;
        }
    }
</script>

<?= $this->endSection() ?>