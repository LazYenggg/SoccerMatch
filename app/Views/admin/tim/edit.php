<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Data Tim</h2>

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

    <form action="/dashboard/tim/update/<?= $tim['id'] ?>" method="post" enctype="multipart/form-data">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tim</label>
            <input type="text" name="nama_tim" value="<?= $tim['nama_tim'] ?>" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Kota Asal</label>
                <input type="text" name="kota" value="<?= $tim['kota'] ?>" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Liga</label>
                <select name="liga" class="w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:border-blue-300" required>
                    <?php 
                        $ligas = ['Premier League', 'LaLiga', 'Serie A', 'Bundesliga', 'Ligue 1'];
                        foreach($ligas as $l): 
                    ?>
                        <option value="<?= $l ?>" <?= ($tim['liga'] == $l) ? 'selected' : '' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Stadion</label>
            <input type="text" name="stadion" value="<?= $tim['stadion'] ?>" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="mb-6 flex gap-4 items-start">
            <div class="w-1/4">
                <p class="text-xs text-gray-500 mb-1">Logo Saat Ini:</p>
                <img src="/img/<?= $tim['logo'] ?>" class="w-20 h-20 object-contain border p-2 rounded">
            </div>
            <div class="w-3/4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Logo (Opsional)</label>
                <input type="file" name="logo" class="w-full border rounded px-3 py-2 focus:outline-none bg-gray-50 text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">*Biarkan kosong jika tidak ingin mengganti logo.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="/dashboard/tim" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">Batal</a>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">Update Data</button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>