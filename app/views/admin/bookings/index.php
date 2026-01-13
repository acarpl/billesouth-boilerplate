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
            <h1 class="text-3xl font-bold text-white">Booking Management</h1>
            <p class="text-gray-400">Manage table reservations and bookings</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                <?php echo $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>

        <!-- Bookings Table -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                    <tr>
                        <th class="px-4 py-3">Booking Code</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Table</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Time</th>
                        <th class="px-4 py-3">Duration</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($bookings as $booking): ?>
                    <tr class="border-b border-gray-800 hover:bg-gray-850">
                        <td class="px-4 py-3 font-medium text-white">#<?= $booking->booking_code ?? ''; ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($booking->customer_name ?? 'N/A'); ?></td>
                        <td class="px-4 py-3">#<?= $booking->table_number ?? $booking->table_id ?? 'N/A'; ?></td>
                        <td class="px-4 py-3"><?= isset($booking->start_time) && !empty($booking->start_time) ? date('M d, Y', strtotime($booking->start_time)) : 'N/A'; ?></td>
                        <td class="px-4 py-3"><?= isset($booking->start_time) && !empty($booking->start_time) ? date('H:i', strtotime($booking->start_time)) : 'N/A'; ?> - <?= isset($booking->end_time) && !empty($booking->end_time) ? date('H:i', strtotime($booking->end_time)) : 'N/A'; ?></td>
                        <td class="px-4 py-3"><?= $booking->duration ?? 'N/A'; ?> hours</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full
                                <?php
                                    $status = $booking->status ?? 'unknown';
                                    if($status == 'confirmed' || $status == 'Paid'): echo 'bg-green-500/20 text-green-500';
                                    elseif($status == 'pending' || $status == 'Unpaid'): echo 'bg-yellow-500/20 text-yellow-500';
                                    else: echo 'bg-red-500/20 text-red-500';
                                    endif;
                                ?>">
                                <?= ucfirst($status); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">Rp <?= number_format($booking->total_price ?? 0, 0, ',', '.'); ?></td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-500 hover:text-blue-400">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="text-red-500 hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if(empty($bookings)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>No bookings found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>