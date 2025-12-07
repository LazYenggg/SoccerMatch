<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Pemain</h2>

    <form action="/dashboard/pemain/update/<?= $pemain['id'] ?>" method="post">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Klub / Tim</label>
            <select name="id_tim" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                <?php foreach($tim as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= ($t['id'] == $pemain['id_tim']) ? 'selected' : '' ?>>
                        <?= $t['nama_tim'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pemain</label>
            <input type="text" name="nama_pemain" value="<?= $pemain['nama_pemain'] ?>" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Posisi</label>
            <select name="posisi" class="w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:border-blue-300" required>
                <?php 
                    $posisi = ['Goalkeeper', 'Defender', 'Midfielder', 'Forward'];
                    foreach($posisi as $p): 
                ?>
                    <option value="<?= $p ?>" <?= ($p == $pemain['posisi']) ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="/dashboard/pemain" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">Batal</a>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">Update</button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>