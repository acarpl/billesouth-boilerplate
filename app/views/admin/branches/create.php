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
    </style>
</head>
<body class="bg-gray-900 text-white">
<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Tambah Cabang Baru</h1>
                <p class="text-gray-400">Masukkan detail cabang di bawah ini</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/branches/store" method="POST">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="branch_name" class="block text-sm font-medium text-gray-300 mb-2">Nama Cabang</label>
                            <input type="text" name="branch_name" id="branch_name" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan nama cabang" required>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                            <input type="text" name="address" id="address" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan alamat cabang" required>
                        </div>

                        <div>
                            <label for="phone_wa" class="block text-sm font-medium text-gray-300 mb-2">Telepon/WhatsApp</label>
                            <input type="tel" name="phone_wa" id="phone_wa" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan nomor telepon/WhatsApp" required>
                        </div>

                        <div>
                            <label for="maps_link" class="block text-sm font-medium text-gray-300 mb-2">Link Maps</label>
                            <input type="url" name="maps_link" id="maps_link" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan link Google Maps">
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Simpan Cabang
                        </button>
                        <a href="<?= BASEURL; ?>/admin/branches" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>