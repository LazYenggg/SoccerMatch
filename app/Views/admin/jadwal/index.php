<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Jadwal</h1>
    <a href="/dashboard/jadwal/tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
        <i class="fas fa-plus mr-2"></i> Tambah Jadwal
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden p-6">
    <table id="tableAdmin" class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                <th class="p-3 border-b">Tanggal</th>
                <th class="p-3 border-b">Pertandingan</th>
                <th class="p-3 border-b">Venue</th>
                <th class="p-3 border-b text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($jadwal as $j): ?>
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-3 text-sm">
                    <?= date('d M Y', strtotime($j['tanggal'])) ?><br>
                    <span class="text-gray-500 text-xs"><?= date('H:i', strtotime($j['tanggal'])) ?> WIB</span>
                </td>
                <td class="p-3">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-blue-800"><?= $j['tim_home'] ?></span>
                        <span class="text-gray-400 text-xs">vs</span>
                        <span class="font-bold text-red-800"><?= $j['tim_away'] ?></span>
                    </div>
                </td>
                <td class="p-3 text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt mr-1"></i> <?= $j['venue'] ?>
                </td>
                <td class="p-3 text-center">
                    <a href="/dashboard/jadwal/edit/<?= $j['id'] ?>" class="text-yellow-500 hover:text-yellow-600 mx-1" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="/dashboard/jadwal/hapus/<?= $j['id'] ?>" method="post" class="inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-red-500 hover:text-red-600 mx-1" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#tableAdmin').DataTable();
    });
</script>

<?= $this->endSection() ?>