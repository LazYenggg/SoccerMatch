<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Tambah Tim Baru</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-sm rounded">
            <?php 
                if(is_array(session()->getFlashdata('error'))){
                    foreach(session()->getFlashdata('error') as $err) echo "<p>- $err</p>";
                } else {
                    echo session()->getFlashdata('error');
                }
            ?>
        </div>
    <?php endif; ?>

    <form action="/dashboard/tim/simpan" method="post" enctype="multipart/form-data">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tim</label>
            <input type="text" name="nama_tim" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" placeholder="Contoh: Chelsea FC" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Kota Asal</label>
                <input type="text" name="kota" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" placeholder="Contoh: London" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Liga</label>
                <select name="liga" class="w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:border-blue-300" required>
                    <option value="">-- Pilih Liga --</option>
                    <option value="Premier League">Premier League</option>
                    <option value="LaLiga">LaLiga</option>
                    <option value="Serie A">Serie A</option>
                    <option value="Bundesliga">Bundesliga</option>
                    <option value="Ligue 1">Ligue 1</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Stadion (Venue Home)</label>
            <input type="text" name="stadion" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" placeholder="Contoh: Stamford Bridge" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Logo Tim (PNG/SVG, Max 1MB)</label>
            <input type="file" name="logo" class="w-full border rounded px-3 py-2 focus:outline-none bg-gray-50 text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="/dashboard/tim" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">Simpan Data</button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>