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
                <h1 class="text-3xl font-bold text-white">Tambah Produk Baru</h1>
                <p class="text-gray-400">Masukkan detail produk di bawah ini</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/products/store" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Produk</label>
                            <input type="text" name="name" id="name" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan nama produk" required>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
                            <select name="category_id" id="category_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                <option value="">Pilih kategori</option>
                                <option value="1">Barang Dagangan</option>
                                <option value="2">Makanan & Minuman</option>
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan deskripsi produk"></textarea>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-300 mb-2">Harga (Rp)</label>
                            <input type="number" name="price" id="price" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan harga" required>
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-300 mb-2">Status Aktif</label>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" class="w-4 h-4 text-blue-600 bg-gray-800 border-gray-700 rounded focus:ring-blue-500" checked>
                                <label for="is_active" class="ml-2 text-sm text-gray-300">Produk aktif</label>
                            </div>
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-300 mb-2">Gambar Produk</label>
                            <input type="file" name="image" id="image" accept="image/*" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <p class="mt-1 text-sm text-gray-500">Unggah gambar produk (opsional)</p>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Simpan Produk
                        </button>
                        <a href="<?= BASEURL; ?>/admin/products" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
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