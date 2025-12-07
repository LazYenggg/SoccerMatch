<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System | SoccerMatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl flex flex-col md:flex-row w-full max-w-4xl overflow-hidden">
        
        <div class="md:w-1/2 bg-blue-800 p-10 flex flex-col justify-center items-center text-white text-center">
            <div class="mb-6 animate-bounce">
                <i class="fas fa-futbol text-8xl"></i>
            </div>
            <h2 class="text-3xl font-bold mb-2">SoccerMatch</h2>
            <p class="text-blue-200">Sistem Manajemen Turnamen Terdistribusi</p>
        </div>

        <div class="md:w-1/2 p-10 flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Masuk ke Akun</h3>

            <?php if(session()->getFlashdata('error')): ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Masuk',
                        text: '<?= session()->getFlashdata('error') ?>',
                        confirmButtonColor: '#1e40af'
                    })
                </script>
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '<?= session()->getFlashdata('success') ?>',
                        confirmButtonColor: '#1e40af'
                    })
                </script>
            <?php endif; ?>

            <form action="/login/process" method="post" class="space-y-6">
                
                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 transition" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500 transition" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-800 hover:bg-blue-900 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                    MASUK <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                <p class="text-gray-500">Belum punya akun? <a href="/register" class="text-blue-600 font-bold hover:underline transition">Daftar User</a></p>
                <p class="text-gray-400 text-xs mt-2 italic">Admin Default: admin / 123456</p>
            </div>
        </div>
    </div>

</body>
</html>