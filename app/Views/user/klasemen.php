<?= $this->extend('layout/main') ?>

<?= $this->section('isi_halaman') ?>

<div class="mb-8 text-center" data-aos="fade-down">
    <h1 class="text-3xl font-extrabold text-blue-900">
        🏆 Klasemen Sementara
    </h1>
    <p class="text-gray-500">Update poin dan posisi tim favoritmu.</p>
</div>

<?php foreach($klasemen as $nama_liga => $tim_tim): ?>

<div class="mb-12" data-aos="fade-up">
    <div class="flex items-center gap-3 mb-4 border-b-2 border-gray-200 pb-2">
        <h2 class="text-2xl font-bold text-gray-800"><?= $nama_liga ?></h2>
        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Season 2024/25</span>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-3 text-center w-12">#</th>
                        <th class="px-4 py-3">Tim</th>
                        <th class="px-4 py-3 text-center" title="Main">M</th>
                        <th class="px-4 py-3 text-center" title="Menang">W</th>
                        <th class="px-4 py-3 text-center" title="Seri">D</th>
                        <th class="px-4 py-3 text-center" title="Kalah">L</th>
                        <th class="px-4 py-3 text-center hidden md:table-cell">GF</th>
                        <th class="px-4 py-3 text-center hidden md:table-cell">GA</th>
                        <th class="px-4 py-3 text-center font-bold">GD</th>
                        <th class="px-4 py-3 text-center font-bold text-black bg-gray-200">Pts</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($tim_tim as $row): ?>
                    <?php 
                        // Logika Warna Zona
                        $bgClass = "hover:bg-gray-50";
                        $posClass = "text-gray-500";
                        
                        // Zona UCL (1-4)
                        if($no <= 4) { 
                            $posClass = "bg-green-500 text-white rounded-full w-6 h-6 flex items-center justify-center mx-auto text-xs"; 
                        } 
                        // Zona Degradasi (3 Terbawah - asumsi liga 20 tim)
                        elseif($no >= 18) { 
                            $posClass = "bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center mx-auto text-xs"; 
                        }
                    ?>
                    
                    <tr class="border-b <?= $bgClass ?>">
                        <td class="px-4 py-3 text-center font-medium">
                            <div class="<?= $posClass ?>">
                                <?= $no++ ?>
                            </div>
                        </td>
                        
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap flex items-center gap-3">
                            <img src="/img/<?= $row['logo'] ?>" class="w-8 h-8 object-contain" alt="Logo">
                            <span><?= $row['nama_tim'] ?></span>
                        </td>

                        <td class="px-4 py-3 text-center"><?= $row['main'] ?></td>
                        <td class="px-4 py-3 text-center text-green-600"><?= $row['menang'] ?></td>
                        <td class="px-4 py-3 text-center text-gray-500"><?= $row['seri'] ?></td>
                        <td class="px-4 py-3 text-center text-red-600"><?= $row['kalah'] ?></td>
                        
                        <td class="px-4 py-3 text-center hidden md:table-cell"><?= $row['gm'] ?></td>
                        <td class="px-4 py-3 text-center hidden md:table-cell"><?= $row['gk'] ?></td>
                        
                        <td class="px-4 py-3 text-center font-bold">
                            <?php 
                                $sg = $row['gm'] - $row['gk']; 
                                echo ($sg > 0) ? "+$sg" : $sg; 
                            ?>
                        </td>
                        
                        <td class="px-4 py-3 text-center font-extrabold text-black bg-gray-100 text-base">
                            <?= $row['poin'] ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 border-t text-xs flex gap-4 text-gray-500">
            <div class="flex items-center gap-1"><div class="w-3 h-3 bg-green-500 rounded-full"></div> Champions League</div>
            <div class="flex items-center gap-1"><div class="w-3 h-3 bg-red-500 rounded-full"></div> Relegation</div>
        </div>
    </div>
</div>

<?php endforeach; ?>

<?= $this->endSection() ?>