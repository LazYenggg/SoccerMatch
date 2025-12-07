<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">👋 Selamat Datang, Admin!</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 font-bold">Total Tim</p>
                <p class="text-2xl font-bold text-gray-800"><?= $total_tim ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-yellow-500">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-calendar-check text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 font-bold">Jadwal Main</p>
                <p class="text-2xl font-bold text-gray-800"><?= $total_match ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 font-bold">Selesai</p>
                <p class="text-2xl font-bold text-gray-800"><?= $match_done ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-red-500">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-red-100 p-3 rounded-full">
                <i class="fas fa-broadcast-tower text-red-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 font-bold">Live Now</p>
                <p class="text-2xl font-bold text-gray-800"><?= $match_live ?></p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Jadwal Terbaru</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Home</th>
                    <th class="p-3">Away</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($latest_match as $lm): ?>
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3"><?= date('d M H:i', strtotime($lm['tanggal'])) ?></td>
                    <td class="p-3 font-bold text-blue-800"><?= $lm['home_team'] ?></td>
                    <td class="p-3 font-bold text-red-800"><?= $lm['away_team'] ?></td>
                    <td class="p-3">
                        <?php if($lm['status'] == 'selesai'): ?>
                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Selesai</span>
                        <?php elseif($lm['status'] == 'berlangsung'): ?>
                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded animate-pulse">Live</span>
                        <?php else: ?>
                            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">Belum</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>