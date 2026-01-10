<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $data['judul']; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black flex items-center justify-center min-h-screen py-10">

    <div class="bg-gray-900 p-8 rounded-lg shadow-xl w-96 border border-gray-800">
        <h2 class="text-3xl font-bold text-white mb-2 text-center tracking-widest">BILLE</h2>
        <p class="text-gray-500 text-center text-sm mb-8">Create your membership</p>
        
        <form action="<?= BASEURL; ?>/auth/processRegister" method="POST">
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Nama Lengkap</label>
                <input type="text" name="name" required placeholder="John Doe"
                    class="w-full p-3 rounded bg-gray-800 border border-gray-700 text-white focus:outline-none focus:border-white">
            </div>

            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">WhatsApp Number</label>
                <input type="text" name="phone" required placeholder="08123xxx"
                    class="w-full p-3 rounded bg-gray-800 border border-gray-700 text-white focus:outline-none focus:border-white">
            </div>

            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Email</label>
                <input type="email" name="email" required placeholder="john@example.com"
                    class="w-full p-3 rounded bg-gray-800 border border-gray-700 text-white focus:outline-none focus:border-white">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full p-3 rounded bg-gray-800 border border-gray-700 text-white focus:outline-none focus:border-white">
            </div>

            <div class="mb-6">
                <label class="block text-gray-400 text-sm mb-2">Konfirmasi Password</label>
                <input type="password" name="confirm_password" required
                    class="w-full p-3 rounded bg-gray-800 border border-gray-700 text-white focus:outline-none focus:border-white">
            </div>
            
            <button type="submit" 
                class="w-full bg-white text-black font-bold py-3 rounded hover:bg-gray-200 transition">
                CREATE ACCOUNT
            </button>
        </form>
        
        <p class="text-gray-500 text-xs mt-6 text-center">
            Sudah punya akun? <a href="<?= BASEURL; ?>/auth" class="text-white">Login</a>
        </p>
    </div>

</body>
</html>