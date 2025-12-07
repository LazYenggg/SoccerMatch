<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Custom Scrollbar Halus untuk Sidebar */
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: #1f2937; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background-color: #4b5563; border-radius: 20px; }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex flex-col md:flex-row min-h-screen">
        
        <div class="bg-gray-900 shadow-xl w-full md:w-64 flex-shrink-0 md:fixed md:h-screen z-50 bottom-0 fixed md:bottom-auto sidebar-scroll overflow-y-auto border-t md:border-t-0 border-gray-700">
            
            <div class="md:mt-0 md:w-64 w-full content-center md:content-start text-left justify-between">
                
                <div class="bg-gray-800 p-6 text-center md:text-left border-b border-gray-700 hidden md:block">
                     <a href="/dashboard" class="text-white text-xl font-bold flex items-center gap-3 hover:text-blue-400 transition group">
                        <div class="bg-blue-600 p-2 rounded-lg group-hover:bg-blue-500 transition shadow-lg">
                            <i class="fas fa-futbol text-white"></i>
                        </div>
                        <span class="tracking-wide">SoccerMatch</span>
                    </a>
                    <p class="text-xs text-gray-500 mt-2 uppercase tracking-widest pl-1">Admin Console</p>
                </div>

                <ul class="list-reset flex flex-row md:flex-col py-0 md:py-4 text-center md:text-left justify-around md:justify-start">
                    
                    <li class="flex-1 md:flex-none">
                        <a href="/dashboard" class="block py-4 md:py-3 pl-0 md:pl-6 align-middle text-gray-400 no-underline hover:text-white border-t-2 md:border-t-0 border-gray-900 md:border-l-4 hover:border-blue-500 hover:bg-gray-800 transition duration-300 <?= uri_string() == 'dashboard' ? 'text-white border-blue-500 bg-gray-800' : '' ?>">
                            <i class="fas fa-tachometer-alt pr-0 md:pr-3 text-lg"></i>
                            <span class="pb-1 md:pb-0 text-xs md:text-sm block md:inline-block mt-1 md:mt-0">Dashboard</span>
                        </a>
                    </li>

                    <li class="flex-1 md:flex-none">
                        <a href="/dashboard/tim" class="block py-4 md:py-3 pl-0 md:pl-6 align-middle text-gray-400 no-underline hover:text-white border-t-2 md:border-t-0 border-gray-900 md:border-l-4 hover:border-green-500 hover:bg-gray-800 transition duration-300">
                            <i class="fas fa-shield-alt pr-0 md:pr-3 text-lg"></i>
                            <span class="pb-1 md:pb-0 text-xs md:text-sm block md:inline-block mt-1 md:mt-0">Data Tim</span>
                        </a>
                    </li>

                    <li class="flex-1 md:flex-none">
                        <a href="/dashboard/pemain" class="block py-4 md:py-3 pl-0 md:pl-6 align-middle text-gray-400 no-underline hover:text-white border-t-2 md:border-t-0 border-gray-900 md:border-l-4 hover:border-teal-500 hover:bg-gray-800 transition duration-300">
                            <i class="fas fa-users pr-0 md:pr-3 text-lg"></i>
                            <span class="pb-1 md:pb-0 text-xs md:text-sm block md:inline-block mt-1 md:mt-0">Data Pemain</span>
                        </a>
                    </li>

                    <li class="flex-1 md:flex-none">
                        <a href="/dashboard/jadwal" class="block py-4 md:py-3 pl-0 md:pl-6 align-middle text-gray-400 no-underline hover:text-white border-t-2 md:border-t-0 border-gray-900 md:border-l-4 hover:border-yellow-500 hover:bg-gray-800 transition duration-300">
                            <i class="fas fa-calendar-alt pr-0 md:pr-3 text-lg"></i>
                            <span class="pb-1 md:pb-0 text-xs md:text-sm block md:inline-block mt-1 md:mt-0">Jadwal</span>
                        </a>
                    </li>
                    
                    <li class="flex-1 md:flex-none">
                        <a href="/dashboard/skor" class="block py-4 md:py-3 pl-0 md:pl-6 align-middle text-gray-400 no-underline hover:text-white border-t-2 md:border-t-0 border-gray-900 md:border-l-4 hover:border-red-500 hover:bg-gray-800 transition duration-300">
                            <i class="fas fa-gamepad pr-0 md:pr-3 text-lg"></i>
                            <span class="pb-1 md:pb-0 text-xs md:text-sm block md:inline-block mt-1 md:mt-0">Input Skor</span>
                        </a>
                    </li>

                    <li class="flex-1 md:flex-none md:mt-8">
                        <a href="/logout" class="block py-4 md:py-3 pl-0 md:pl-6 align-middle text-red-400 no-underline hover:text-red-200 border-t-2 md:border-t-0 border-gray-900 md:border-l-4 hover:border-red-600 hover:bg-gray-800 transition duration-300">
                            <i class="fas fa-sign-out-alt pr-0 md:pr-3 text-lg"></i>
                            <span class="pb-1 md:pb-0 text-xs md:text-sm block md:inline-block mt-1 md:mt-0">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex-1 bg-gray-100 md:ml-64 min-h-screen transition-all duration-300 flex flex-col">
            
            <div class="bg-gray-900 text-white p-4 shadow-lg md:hidden flex justify-between items-center sticky top-0 z-40">
                 <div class="flex items-center gap-3">
                     <div class="bg-blue-600 p-1.5 rounded-lg shadow">
                        <i class="fas fa-futbol"></i>
                     </div>
                     <span class="font-bold text-lg tracking-wide">SoccerMatch</span>
                 </div>
                 <a href="/logout" class="text-xs text-red-300 hover:text-white bg-gray-800 hover:bg-red-600 px-3 py-1.5 rounded-full transition border border-gray-700">
                    <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                 </a>
            </div>

            <div class="p-6 md:p-10 mb-20 md:mb-0 flex-grow">
                
                <?php if(session()->getFlashdata('success')): ?>
                    <script>
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        Toast.fire({
                            icon: 'success',
                            title: '<?= session()->getFlashdata('success') ?>'
                        });
                    </script>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: '<?= session()->getFlashdata('error') ?>',
                            confirmButtonColor: '#d33'
                        });
                    </script>
                <?php endif; ?>

                <?= $this->renderSection('konten_admin') ?>
                
            </div>

            <div class="p-4 text-center text-gray-400 text-xs hidden md:block">
                &copy; <?= date('Y') ?> SoccerMatch System - Sistem Terdistribusi
            </div>
        </div>

    </div>

</body>
</html>