<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome (Ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body { font-family: 'Montserrat', sans-serif; }
        .nav-link:hover { color: #ffffff; border-bottom: 2px solid white; }
    </style>
</head>
<body class="bg-black text-white">

<!-- Navbar -->
<nav class="bg-black/80 backdrop-blur-md border-b border-gray-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="<?= BASEURL; ?>/home" class="text-2xl font-bold tracking-[0.3em] text-white">BILLE</a>
            </div>

            <!-- Menu Utama -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8">
                    <a href="<?= BASEURL; ?>/home" class="text-gray-300 px-3 py-2 text-sm font-medium nav-link">HOME</a>
                    <a href="<?= BASEURL; ?>/booking" class="text-gray-300 px-3 py-2 text-sm font-medium nav-link">BOOKING</a>
                    <a href="<?= BASEURL; ?>/shop" class="text-gray-300 px-3 py-2 text-sm font-medium nav-link">SHOP</a>
                    <a href="<?= BASEURL; ?>/events" class="text-gray-300 px-3 py-2 text-sm font-medium nav-link">EVENTS</a>
                </div>
            </div>

            <!-- Tombol Login / Akun -->
            <div class="flex items-center space-x-4">
                <?php if(isset($_SESSION['user_id'])) : ?>
                    <!-- Jika Sudah Login -->
                    <div class="text-sm font-medium text-gray-400 mr-2">Halo, <?= explode(' ', $_SESSION['user_name'])[0]; ?></div>
                    <a href="<?= BASEURL; ?>/profile" class="text-white hover:text-gray-300">
                        <i class="fa-regular fa-circle-user text-xl"></i>
                    </a>
                    <a href="<?= BASEURL; ?>/auth/logout" class="text-red-500 hover:text-red-400 text-sm font-bold ml-4">LOGOUT</a>
                <?php else : ?>
                    <!-- Jika Belum Login -->
                    <a href="<?= BASEURL; ?>/auth" class="text-gray-300 hover:text-white text-sm font-medium">LOGIN</a>
                    <a href="<?= BASEURL; ?>/auth/register" class="bg-white text-black px-5 py-2 rounded-full text-sm font-bold hover:bg-gray-200 transition">REGISTER</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>