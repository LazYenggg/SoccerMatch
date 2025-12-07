<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Tambah Pemain</h2>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 text-sm rounded">
            <p><?= session()->getFlashdata('error') ?></p>
        </div>
    <?php endif; ?>

    <form action="/dashboard/pemain/simpan" method="post">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Klub / Tim</label>
            <select name="id_tim" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" required>
                <option value="">-- Pilih Tim --</option>
                <?php foreach($tim as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= $t['nama_tim'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pemain</label>
            <input type="text" name="nama_pemain" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" placeholder="Contoh: Erling Haaland" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Posisi</label>
            <select name="posisi" class="w-full border rounded px-3 py-2 bg-white focus:outline-none focus:ring focus:border-blue-300" required>
                <option value="">-- Pilih Posisi --</option>
                <option value="Goalkeeper">Goalkeeper (GK)</option>
                <option value="Defender">Defender (Bek)</option>
                <option value="Midfielder">Midfielder (Gelandang)</option>
                <option value="Forward">Forward / Striker (Penyerang)</option>
            </select>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="/dashboard/pemain" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">Batal</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">Simpan</button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>