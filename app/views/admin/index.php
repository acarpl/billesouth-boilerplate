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
            <h1 class="text-3xl font-bold text-white">Dashboard</h1>
            <p class="text-gray-400">Welcome back, Admin!</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="text-gray-400 mb-2">Total Tables</div>
                <div class="text-3xl font-bold text-white"><?php echo count($data['tables']); ?></div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="text-gray-400 mb-2">Active Bookings</div>
                <div class="text-3xl font-bold text-white"><?php echo $data['active_bookings_count']; ?></div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="text-gray-400 mb-2">Total Revenue</div>
                <div class="text-3xl font-bold text-white">Rp <?php echo number_format($data['total_revenue'], 0, ',', '.'); ?></div>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="text-gray-400 mb-2">Members</div>
                <div class="text-3xl font-bold text-white"><?php echo $data['members_count']; ?></div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
            <h2 class="text-xl font-bold text-white mb-4">Recent Bookings</h2>
            <div class="overflow-x-auto">
                <?php if (!empty($data['recent_bookings'])): ?>
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Booking ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        <?php foreach($data['recent_bookings'] as $booking): ?>
                        <tr>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-white"><?= htmlspecialchars($booking->booking_code); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-white"><?= htmlspecialchars($booking->customer_name); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-white"><?= date('M j, Y', strtotime($booking->start_time)); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-white">Rp <?= number_format($booking->total_price, 0, ',', '.'); ?></td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php if($booking->payment_status === 'Paid'): ?>
                                        bg-green-800 text-green-200
                                    <?php elseif($booking->payment_status === 'Unpaid'): ?>
                                        bg-yellow-800 text-yellow-200
                                    <?php else: ?>
                                        bg-red-800 text-red-200
                                    <?php endif; ?>">
                                    <?= ucfirst($booking->payment_status); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p class="text-gray-400">No recent bookings found.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>