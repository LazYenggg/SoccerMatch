<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Master Data Tim</h1>
    <a href="/dashboard/tim/tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition shadow">
        <i class="fas fa-plus mr-2"></i> Tambah Tim
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden p-6">
    <table id="tableTim" class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                <th class="p-3 border-b">Logo</th>
                <th class="p-3 border-b">Nama Tim</th>
                <th class="p-3 border-b">Liga</th>
                <th class="p-3 border-b">Stadion</th>
                <th class="p-3 border-b text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tims as $t): ?>
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-3">
                    <img src="/img/<?= $t['logo'] ?>" class="w-10 h-10 object-contain" 
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= $t['nama_tim'] ?>'">
                </td>
                <td class="p-3 font-bold text-gray-800"><?= $t['nama_tim'] ?></td>
                <td class="p-3">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                        <?= $t['liga'] ?>
                    </span>
                </td>
                <td class="p-3 text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i> <?= $t['stadion'] ?>, <?= $t['kota'] ?>
                </td>
                <td class="p-3 text-center">
                    <a href="/dashboard/tim/edit/<?= $t['id'] ?>" class="text-yellow-500 hover:text-yellow-600 mx-1">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="/dashboard/tim/hapus/<?= $t['id'] ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus tim ini? Data jadwal & pemain terkait akan ikut terhapus!');">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="text-red-500 hover:text-red-600 mx-1">
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
        $('#tableTim').DataTable({
            "pageLength": 10,
            "order": [[ 2, "asc" ]] // Default sort by Liga
        });
    });
</script>

<?= $this->endSection() ?>