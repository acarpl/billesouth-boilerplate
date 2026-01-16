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
            <h1 class="text-3xl font-bold text-white">Daily Revenue Report</h1>
            <p class="text-gray-400">Revenue details for <?= date('F j, Y', strtotime($date)); ?></p>
        </div>

        <!-- Daily Summary Cards -->
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
                        <i class="fas fa-chart-line text-purple-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Orders</p>
                        <p class="text-2xl font-bold text-white">
                            <?= count($orders); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-4">Orders for <?= date('F j, Y', strtotime($date)); ?></h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-400">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                        <tr>
                            <th class="px-4 py-3">Order ID</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                        <tr class="border-b border-gray-800 hover:bg-gray-850">
                            <td class="px-4 py-3 font-medium text-white">#<?= $order->id; ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($order->customer_name ?? 'Guest'); ?></td>
                            <td class="px-4 py-3">Rp <?= number_format($order->total_amount, 0, ',', '.'); ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?php 
                                        if($order->status == 'paid'): echo 'bg-green-500/20 text-green-500';
                                        elseif($order->status == 'pending'): echo 'bg-yellow-500/20 text-yellow-500';
                                        else: echo 'bg-red-500/20 text-red-500';
                                        endif; 
                                    ?>">
                                    <?= ucfirst($order->status); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3"><?= date('H:i', strtotime($order->created_at)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($orders)): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">No orders found for this date</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>