<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Yearly Revenue Report</h1>
            <p class="text-gray-400">Revenue details for <?= $year; ?></p>
        </div>

        <!-- Yearly Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 mr-4">
                        <i class="fas fa-shopping-cart text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Orders</p>
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
                        <p class="text-gray-400 text-sm">Total Revenue</p>
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
                        <p class="text-gray-400 text-sm">Avg. Order Value</p>
                        <p class="text-2xl font-bold text-white">
                            Rp <?= $revenue_data ? number_format($revenue_data->average_order_value, 0, ',', '.') : '0'; ?>
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
                        <p class="text-gray-400 text-sm">Months Active</p>
                        <p class="text-2xl font-bold text-white">
                            <?= count($monthly_breakdown); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Breakdown Chart -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Monthly Revenue Breakdown</h2>
            <div class="overflow-x-auto">
                <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Monthly Breakdown Table -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-4">Monthly Revenue Details</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                        <tr>
                            <th class="px-4 py-3">Month</th>
                            <th class="px-4 py-3">Orders</th>
                            <th class="px-4 py-3">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($monthly_breakdown as $month_data): ?>
                        <tr class="border-b border-gray-800 hover:bg-gray-850">
                            <td class="px-4 py-3 font-medium text-white"><?= date('F Y', strtotime($month_data->month . '-01')); ?></td>
                            <td class="px-4 py-3"><?= $month_data->total_orders; ?></td>
                            <td class="px-4 py-3">Rp <?= number_format($month_data->monthly_revenue, 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($monthly_breakdown)): ?>
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

<?php foreach($monthly_breakdown as $month_data): ?>
months.push('<?= date('M', strtotime($month_data->month . '-01')); ?>');
revenues.push(<?= $month_data->monthly_revenue; ?>);
<?php endforeach; ?>

// Create chart
const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
new Chart(ctx, {
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
</script>