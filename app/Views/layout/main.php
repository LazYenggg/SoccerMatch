<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SoccerMatch' ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <nav class="bg-blue-900 text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex flex-wrap justify-between items-center">
            <a href="/" class="text-2xl font-bold flex items-center gap-2 hover:text-blue-200 transition">
                <i class="fas fa-futbol"></i> SoccerMatch
            </a>

            <div class="flex items-center gap-4">
                <a href="/jadwal" class="hover:text-blue-300 transition font-medium">Jadwal</a>
                <a href="/klasemen" class="hover:text-blue-300 transition font-medium">Klasemen</a>
                
                <?php if(session()->get('logged_in')): ?>
                    
                    <span class="hidden md:inline text-blue-200 text-sm">
                        Hai, <b><?= session()->get('username') ?></b>
                    </span>

                    <?php if(session()->get('role') == 'admin'): ?>
                        <a href="/dashboard" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-full text-xs md:text-sm font-bold transition shadow-md flex items-center gap-1">
                            <i class="fas fa-tachometer-alt"></i> <span class="hidden md:inline">Panel</span>
                        </a>
                    <?php endif; ?>

                    <a href="/logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full text-xs md:text-sm font-bold transition shadow-md flex items-center gap-1">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>

                <?php else: ?>
                    
                    <a href="/login" class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-2 rounded-full text-sm font-bold transition shadow-lg transform hover:scale-105 flex items-center gap-1">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>

                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-4 md:p-6 flex-grow">
        <?= $this->renderSection('isi_halaman') ?>
    </main>

    <footer class="bg-gray-900 text-gray-400 p-8 text-center mt-auto">
        <p class="font-semibold text-gray-300">&copy; <?= date('Y') ?> SoccerMatch</p>
        <p class="text-xs mt-2 text-gray-600">Sistem Terdistribusi Project - CodeIgniter 4 & Docker</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        AOS.init({ duration: 800, once: true });

        // Global Toast Notification (Untuk Login/Logout/Register)
        <?php if(session()->getFlashdata('success')) : ?>
            Toastify({
                text: "<?= session()->getFlashdata('success') ?>",
                duration: 3000,
                gravity: "top", 
                position: "right", 
                style: { background: "linear-gradient(to right, #00b09b, #96c93d)" }
            }).showToast();
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= session()->getFlashdata('error') ?>',
            });
        <?php endif; ?>
    </script>
    
    <?= $this->renderSection('script_bawah') ?>
</body>
</html>