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
                <h1 class="text-3xl font-bold text-white">Process Payment</h1>
                <p class="text-gray-400">Complete payment for booking #<?= $data['booking']->booking_code; ?></p>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                    <?php echo $_SESSION['flash_message']; ?>
                    <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <!-- Booking Details -->
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-white mb-4">Booking Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-400 text-sm">Booking Code</p>
                            <p class="text-white font-medium">#<?= $data['booking']->booking_code; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Customer</p>
                            <p class="text-white font-medium"><?= $data['booking']->customer_name ?? 'Walk-in Customer'; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Table</p>
                            <p class="text-white font-medium">Table #<?= $data['booking']->table_id; ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Date & Time</p>
                            <p class="text-white font-medium"><?= date('M d, Y H:i', strtotime($data['booking']->start_time)); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Duration</p>
                            <p class="text-white font-medium"><?= $data['booking']->duration; ?> hours</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Total Price</p>
                            <p class="text-white font-medium">Rp <?= number_format($data['booking']->total_price, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form action="<?= BASEURL; ?>/admin/processPayment" method="POST">
                    <input type="hidden" name="booking_code" value="<?= $data['booking']->booking_code; ?>">

                    <div class="mb-6">
                        <label for="payment_method" class="block text-sm font-medium text-gray-300 mb-2">Payment Method *</label>
                        <select name="payment_method" id="payment_method" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="">Select payment method</option>
                            <option value="Cash">Cash</option>
                            <option value="Debit Card">Debit Card</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <div class="mb-6 p-4 bg-gray-800 rounded-lg">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="text-white">Rp <?= number_format($data['booking']->total_price, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-white">
                            <span>Total to Pay</span>
                            <span>Rp <?= number_format($data['booking']->total_price, 0, ',', '.'); ?></span>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg">
                            Confirm Payment
                        </button>
                        <a href="<?= BASEURL; ?>/admin" class="flex-1 bg-gray-700 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg text-center">
                            Cancel
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