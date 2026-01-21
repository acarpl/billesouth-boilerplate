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
    <main class="flex-1 p-8 bg-gray-950 min-h-screen ml-64">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Laporan Pendapatan Tahunan</h1>
                <p class="text-gray-500 text-sm uppercase tracking-widest mt-1">Rincian pendapatan untuk <?= $year ?? date('Y'); ?></p>
            </div>
            <div class="flex items-center gap-3 bg-gray-900/50 p-2 rounded-lg border border-gray-800">
                <div class="text-right hidden sm:block">
                    <p class="text-xs text-gray-500 font-bold uppercase"><?= $_SESSION['user_name']; ?></p>
                    <p class="text-[10px] text-blue-400 uppercase tracking-tighter"><?= $_SESSION['user_role']; ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-purple-600 flex items-center justify-center font-bold">
                    <?= strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </div>
            </div>
        </div>

        <!-- Yearly Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 mr-4">
                        <i class="fas fa-shopping-cart text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Pesanan</p>
                        <p class="text-2xl font-bold text-white">
                            <?= isset($revenue_data) && $revenue_data ? ($revenue_data->total_orders ?? 0) : 0; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500/20 mr-4">
                        <i class="fas fa-dollar-sign text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-white">
                            Rp <?= isset($revenue_data) && $revenue_data ? number_format($revenue_data->total_revenue ?? 0, 0, ',', '.') : '0'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500/20 mr-4">
                        <i class="fas fa-tag text-yellow-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Nilai Rata-rata Pesanan</p>
                        <p class="text-2xl font-bold text-white">
                            Rp <?= isset($revenue_data) && $revenue_data ? number_format($revenue_data->average_order_value ?? 0, 0, ',', '.') : '0'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500/20 mr-4">
                        <i class="fas fa-chart-pie text-purple-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Bulan Aktif</p>
                        <p class="text-2xl font-bold text-white">
                            <?= isset($monthly_breakdown) ? count($monthly_breakdown) : 0; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Rincian Bulanan -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Rincian Pendapatan Bulanan</h2>
            <div class="overflow-x-auto">
                <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Tabel Rincian Bulanan -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-4">Rincian Pendapatan Bulanan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                        <tr>
                            <th class="px-4 py-3">Bulan</th>
                            <th class="px-4 py-3">Pesanan</th>
                            <th class="px-4 py-3">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($monthly_breakdown) && is_array($monthly_breakdown)): ?>
                        <?php foreach($monthly_breakdown as $month_data): ?>
                        <tr class="border-b border-gray-800 hover:bg-gray-850">
                            <td class="px-4 py-3 font-medium text-white"><?= date('F Y', strtotime($month_data->month . '-01')); ?></td>
                            <td class="px-4 py-3"><?= $month_data->total_orders ?? 0; ?></td>
                            <td class="px-4 py-3">Rp <?= number_format($month_data->monthly_revenue ?? 0, 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">No revenue data found for this year</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare data for chart
const months = [];
const revenues = [];

<?php if(isset($monthly_breakdown) && is_array($monthly_breakdown)): ?>
<?php foreach($monthly_breakdown as $month_data): ?>
months.push('<?= date('M', strtotime($month_data->month . '-01')); ?>');
revenues.push(<?= $month_data->monthly_revenue ?? 0; ?>);
<?php endforeach; ?>
<?php endif; ?>

// Create chart
const ctx = document.getElementById('monthlyRevenueChart');
if (ctx) {
    const chartCtx = ctx.getContext('2d');
    new Chart(chartCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Monthly Revenue (Rp)',
                data: revenues,
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>