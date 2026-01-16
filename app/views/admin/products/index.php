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
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Manajemen Produk</h1>
            <p class="text-gray-400">Kelola produk dan inventaris tempat biliar Anda</p>
        </div>

        <div class="mb-6">
            <a href="<?= BASEURL; ?>/admin/products/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Produk Baru
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                <?php echo $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>

        <!-- Products Table -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                    <tr>
                        <th class="px-4 py-3">Gambar</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Stok</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $product): ?>
                    <tr class="border-b border-gray-800 hover:bg-gray-850">
                        <td class="px-4 py-3">
                            <?php if($product->image): ?>
                                <img src="<?= BASEURL . '/../' . $product->image; ?>" alt="<?= htmlspecialchars($product->name); ?>" class="w-12 h-12 object-cover rounded">
                            <?php else: ?>
                                <div class="w-12 h-12 bg-gray-700 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-gray-500"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 font-medium text-white"><?= htmlspecialchars($product->name); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-500">
                                <?php
                                    if($product->category_id == 1): echo 'Barang Dagangan';
                                    elseif($product->category_id == 2): echo 'Makanan & Minuman';
                                    else: echo 'Tidak Diketahui';
                                    endif;
                                ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">Rp <?= number_format($product->price, 0, ',', '.'); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full
                                <?php
                                    $stock = $product->stock ?? 0;
                                    if($stock > 10): echo 'bg-green-500/20 text-green-500';
                                    elseif($stock > 0): echo 'bg-yellow-500/20 text-yellow-500';
                                    else: echo 'bg-red-500/20 text-red-500';
                                    endif;
                                ?>">
                                <?= $stock; ?> tersisa
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="<?= BASEURL; ?>/admin/products/edit/<?= $product->id; ?>" class="text-blue-500 hover:text-blue-400">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" onclick="confirmDelete(<?= $product->id; ?>)" class="text-red-500 hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if(empty($products)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Tidak ada produk ditemukan. <a href="<?= BASEURL; ?>/admin/products/create" class="text-blue-500 hover:underline">Tambahkan produk pertama Anda</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function confirmDelete(id) {
    if(confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        // Create a form and submit it to delete the product
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASEURL; ?>/admin/products/destroy/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>