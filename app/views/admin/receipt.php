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
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-content, .receipt-content * {
                visibility: visible;
            }
            .receipt-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
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
                <h1 class="text-3xl font-bold text-white">Booking Receipt</h1>
                <p class="text-gray-400">Receipt for booking #<?= $data['booking']->booking_code; ?></p>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                    <?php echo $_SESSION['flash_message']; ?>
                    <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
                </div>
            <?php endif; ?>

            <div class="receipt-content">
                <div class="bg-gray-900 rounded-lg p-8 border border-gray-800">
                    <!-- Receipt Header -->
                    <div class="text-center mb-6">
                        <h1 class="text-2xl font-bold text-white">BILLE BILLIARDS</h1>
                        <p class="text-gray-400 text-sm">Professional Billiard Service</p>
                    </div>

                    <!-- Receipt Info -->
                    <div class="border-b border-gray-700 pb-4 mb-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400">Receipt #: </p>
                                <p class="text-white">#<?= $data['booking']->booking_code; ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-400">Date: </p>
                                <p class="text-white"><?= date('M d, Y H:i', strtotime($data['booking']->created_at ?? date('Y-m-d H:i:s'))); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-white mb-2">Customer Information</h3>
                        <p class="text-white"><strong>Name:</strong> <?= $data['booking']->customer_name ?? 'Walk-in Customer'; ?></p>
                        <?php if($data['booking']->customer_phone): ?>
                            <p class="text-white"><strong>Phone:</strong> <?= $data['booking']->customer_phone; ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Booking Details -->
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-white mb-2">Booking Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400">Table Number</p>
                                <p class="text-white">#<?= $data['table']->table_number; ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">Table Type</p>
                                <p class="text-white"><?= $data['table']->type; ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">Start Time</p>
                                <p class="text-white"><?= date('M d, Y H:i', strtotime($data['booking']->start_time)); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">End Time</p>
                                <p class="text-white"><?= date('M d, Y H:i', strtotime($data['booking']->end_time)); ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">Duration</p>
                                <p class="text-white"><?= $data['booking']->duration; ?> hours</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Rate</p>
                                <p class="text-white">Rp <?= number_format($data['table']->price_per_hour, 0, ',', '.'); ?>/hour</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-white mb-2">Payment Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400">Payment Method</p>
                                <p class="text-white"><?= $data['booking']->payment_method; ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400">Payment Status</p>
                                <p class="text-green-500 font-bold">PAID</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="border-t border-gray-700 pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-400">Subtotal</span>
                            <span class="text-white">Rp <?= number_format($data['table']->price_per_hour * $data['booking']->duration, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-400">Discount</span>
                            <span class="text-white">-Rp <?= number_format(($data['table']->price_per_hour * $data['booking']->duration) - $data['booking']->total_price, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-white border-t border-gray-700 pt-2">
                            <span>Total Paid</span>
                            <span>Rp <?= number_format($data['booking']->total_price, 0, ',', '.'); ?></span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 text-center">
                        <p class="text-gray-400 text-sm">Thank you for choosing Bille Billiards!</p>
                        <p class="text-gray-400 text-xs mt-2">For inquiries: contact@billebilliards.com | 0800-123-4567</p>
                    </div>
                </div>

                <!-- Print Button -->
                <div class="mt-6 flex justify-center">
                    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        <i class="fas fa-print mr-2"></i>Print Receipt
                    </button>
                    <a href="<?= BASEURL; ?>/admin" class="ml-4 bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>