<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('konten_admin') ?>

<h1 class="text-2xl font-bold text-gray-800 mb-6">Kelola Skor & Live Match</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($matches as $m): ?>
    
    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition border-t-4 
        <?= ($m['status'] == 'berlangsung') ? 'border-red-500' : (($m['status'] == 'selesai') ? 'border-green-500' : 'border-blue-500') ?>">
        
        <div class="p-5 text-center">
            <div class="mb-4">
                <?php if($m['status'] == 'berlangsung'): ?>
                    <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full animate-pulse">● LIVE NOW</span>
                <?php elseif($m['status'] == 'halftime'): ?>
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full">HALF TIME</span>
                <?php elseif($m['status'] == 'selesai'): ?>
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">FULL TIME</span>
                <?php else: ?>
                    <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full">
                        <?= date('d M Y • H:i', strtotime($m['tanggal'])) ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="flex justify-between items-center mb-4">
                <div class="w-1/3">
                    <img src="/img/<?= $m['home_logo'] ?>" class="w-12 h-12 mx-auto mb-2 object-contain">
                    <h3 class="font-bold text-gray-800 text-sm"><?= $m['home_team'] ?></h3>
                </div>
                
                <div class="w-1/3 font-mono text-3xl font-bold text-gray-700">
                    <?= $m['skor_a'] ?? 0 ?> - <?= $m['skor_b'] ?? 0 ?>
                </div>

                <div class="w-1/3">
                    <img src="/img/<?= $m['away_logo'] ?>" class="w-12 h-12 mx-auto mb-2 object-contain">
                    <h3 class="font-bold text-gray-800 text-sm"><?= $m['away_team'] ?></h3>
                </div>
            </div>

            <a href="/dashboard/live/<?= $m['id'] ?>" class="block w-full py-2 rounded-lg font-bold text-white transition
                <?= ($m['status'] == 'selesai') ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700' ?>">
                <i class="fas fa-gamepad mr-2"></i> 
                <?= ($m['status'] == 'selesai') ? 'Lihat Detail' : 'Buka Console' ?>
            </a>
        </div>
    </div>

    <?php endforeach; ?>
</div>

<?= $this->endSection() ?>