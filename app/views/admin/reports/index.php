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
            <h1 class="text-3xl font-bold text-white">Laporan Pendapatan</h1>
            <p class="text-gray-400">Analisis pendapatan dan penjualan tempat biliar Anda</p>
        </div>

        <!-- Revenue Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 mr-4">
                        <i class="fas fa-calendar-day text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Pendapatan Hari Ini</p>
                        <p class="text-2xl font-bold text-white">
                            <?php if($daily_revenue): ?>
                                Rp <?= number_format($daily_revenue->revenue ?? 0, 0, ',', '.'); ?>
                            <?php else: ?>
                                Rp 0
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500/20 mr-4">
                        <i class="fas fa-calendar-week text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Pendapatan Bulan Ini</p>
                        <p class="text-2xl font-bold text-white">
                            <?php if($monthly_revenue): ?>
                                Rp <?= number_format($monthly_revenue->revenue ?? 0, 0, ',', '.'); ?>
                            <?php else: ?>
                                Rp 0
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500/20 mr-4">
                        <i class="fas fa-calendar-alt text-purple-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Pendapatan Tahun Ini</p>
                        <p class="text-2xl font-bold text-white">
                            <?php if($yearly_revenue): ?>
                                Rp <?= number_format($yearly_revenue->revenue ?? 0, 0, ',', '.'); ?>
                            <?php else: ?>
                                Rp 0
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Selling Products -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h2 class="text-xl font-bold text-white mb-4">Produk Terlaris</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Jumlah Terjual</th>
                                <th class="px-4 py-3">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($top_products as $product): ?>
                            <tr class="border-b border-gray-800 hover:bg-gray-850">
                                <td class="px-4 py-3 font-medium text-white"><?= htmlspecialchars($product->name); ?></td>
                                <td class="px-4 py-3"><?= $product->total_quantity; ?></td>
                                <td class="px-4 py-3">Rp <?= number_format($product->total_revenue, 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>

                            <?php if(empty($top_products)): ?>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-center text-gray-500">No sales data available</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Report Selection -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h2 class="text-xl font-bold text-white mb-4">Hasilkan Laporan</h2>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-white mb-2">Laporan Harian</h3>
                        <form action="<?= BASEURL; ?>/admin/reports/daily" method="GET" class="flex">
                            <input type="date" name="date" value="<?= date('Y-m-d'); ?>" class="bg-gray-800 border border-gray-700 text-white rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-lg">
                                Lihat
                            </button>
                        </form>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-white mb-2">Laporan Bulanan</h3>
                        <form action="<?= BASEURL; ?>/admin/reports/monthly" method="GET" class="flex">
                            <input type="month" name="month" value="<?= date('Y-m'); ?>" class="bg-gray-800 border border-gray-700 text-white rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-lg">
                                Lihat
                            </button>
                        </form>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-white mb-2">Laporan Tahunan</h3>
                        <form action="<?= BASEURL; ?>/admin/reports/yearly" method="GET" class="flex">
                            <input type="number" name="year" value="<?= date('Y'); ?>" min="2020" max="<?= date('Y'); ?>" class="bg-gray-800 border border-gray-700 text-white rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-lg">
                                Lihat
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>