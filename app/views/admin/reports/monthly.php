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
            <h1 class="text-3xl font-bold text-white">Laporan Pendapatan Bulanan</h1>
            <p class="text-gray-400">Rincian pendapatan untuk <?= date('F Y', strtotime($month . '-01')); ?></p>
        </div>

        <!-- Monthly Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 mr-4">
                        <i class="fas fa-shopping-cart text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Pesanan</p>
                        <p class="text-2xl font-bold text-white">
                            <?= $revenue_data ? $revenue_data->total_orders : 0; ?>
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
                            Rp <?= $revenue_data ? number_format($revenue_data->total_revenue, 0, ',', '.') : '0'; ?>
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
                            Rp <?= $revenue_data ? number_format($revenue_data->average_order_value, 0, ',', '.') : '0'; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500/20 mr-4">
                        <i class="fas fa-chart-bar text-purple-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Hari Aktif</p>
                        <p class="text-2xl font-bold text-white">
                            <?= count($daily_breakdown); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Rincian Harian -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Rincian Pendapatan Harian</h2>
            <div class="overflow-x-auto">
                <canvas id="dailyRevenueChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Tabel Rincian Harian -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-4">Rincian Pendapatan Harian</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Pesanan</th>
                            <th class="px-4 py-3">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($daily_breakdown as $day): ?>
                        <tr class="border-b border-gray-800 hover:bg-gray-850">
                            <td class="px-4 py-3 font-medium text-white"><?= date('M j, Y', strtotime($day->date)); ?></td>
                            <td class="px-4 py-3"><?= $day->total_orders; ?></td>
                            <td class="px-4 py-3">Rp <?= number_format($day->daily_revenue, 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($daily_breakdown)): ?>
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">No revenue data found for this month</td>
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
const dates = [];
const revenues = [];

<?php foreach($daily_breakdown as $day): ?>
dates.push('<?= date('M j', strtotime($day->date)); ?>');
revenues.push(<?= $day->daily_revenue; ?>);
<?php endforeach; ?>

// Create chart
const ctx = document.getElementById('dailyRevenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: 'Daily Revenue (Rp)',
            data: revenues,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.1
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
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>