<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Pemain</h1>
    <a href="/dashboard/pemain/tambah" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition shadow">
        <i class="fas fa-plus mr-2"></i> Tambah Pemain
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden p-6">
    <table id="tablePemain" class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                <th class="p-3 border-b">Nama Pemain</th>
                <th class="p-3 border-b">Tim</th>
                <th class="p-3 border-b">Posisi</th>
                <th class="p-3 border-b text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pemain as $p): ?>
            <tr class="hover:bg-gray-50 border-b">
                <td class="p-3 font-bold text-gray-800"><?= $p['nama_pemain'] ?></td>
                <td class="p-3 flex items-center gap-2">
                    <img src="/img/<?= $p['logo'] ?>" class="w-6 h-6 object-contain" onerror="this.src='https://ui-avatars.com/api/?name=<?= $p['nama_tim'] ?>'">
                    <span class="text-sm"><?= $p['nama_tim'] ?></span>
                </td>
                <td class="p-3">
                    <?php 
                        $badge = "bg-gray-100 text-gray-800";
                        if($p['posisi'] == 'Striker' || $p['posisi'] == 'Forward') $badge = "bg-red-100 text-red-800";
                        elseif($p['posisi'] == 'Midfielder') $badge = "bg-green-100 text-green-800";
                        elseif($p['posisi'] == 'Defender') $badge = "bg-blue-100 text-blue-800";
                        elseif($p['posisi'] == 'Goalkeeper') $badge = "bg-yellow-100 text-yellow-800";
                    ?>
                    <span class="<?= $badge ?> text-xs font-semibold px-2 py-1 rounded">
                        <?= $p['posisi'] ?>
                    </span>
                </td>
                <td class="p-3 text-center">
                    <a href="/dashboard/pemain/edit/<?= $p['id'] ?>" class="text-yellow-500 hover:text-yellow-600 mx-1">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="/dashboard/pemain/hapus/<?= $p['id'] ?>" method="post" class="inline" onsubmit="return confirm('Yakin hapus pemain ini?');">
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
        $('#tablePemain').DataTable();
    });
</script>

<?= $this->endSection() ?>