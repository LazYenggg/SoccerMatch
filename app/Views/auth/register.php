<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | SoccerMatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-to-br from-green-800 to-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-2xl flex flex-col md:flex-row w-full max-w-4xl overflow-hidden">
        
        <div class="md:w-1/2 bg-green-700 p-10 flex flex-col justify-center items-center text-white text-center">
            <div class="mb-6">
                <i class="fas fa-users text-8xl"></i>
            </div>
            <h2 class="text-3xl font-bold mb-2">Gabung Komunitas</h2>
            <p class="text-green-200">Daftar sekarang untuk memantau skor tim favoritmu secara realtime!</p>
        </div>

        <div class="md:w-1/2 p-10 flex flex-col justify-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Buat Akun Baru</h3>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm">
                    <?php 
                    if(is_array(session()->getFlashdata('error'))){
                        foreach(session()->getFlashdata('error') as $err) echo "<p>- $err</p>";
                    } else {
                        echo session()->getFlashdata('error');
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="/login/process_register" method="post" class="space-y-4">
                
                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-1">Username</label>
                    <input type="text" name="username" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500" placeholder="Pilih username unik" required>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500" placeholder="Minimal 4 karakter" required>
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-semibold mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_conf" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500" placeholder="Ketik ulang password" required>
                </div>

                <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 rounded-lg transition duration-300">
                    DAFTAR SEKARANG
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                <p class="text-gray-500">Sudah punya akun? <a href="/login" class="text-green-700 font-bold hover:underline">Login di sini</a></p>
            </div>
        </div>
    </div>

</body>
</html>