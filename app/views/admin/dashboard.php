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
            <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
            <p class="text-gray-400">Manage your billiard parlor operations</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 mr-4">
                        <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Bookings</p>
                        <p class="text-2xl font-bold text-white"><?= $data['total_bookings']; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500/20 mr-4">
                        <i class="fas fa-box text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Products</p>
                        <p class="text-2xl font-bold text-white"><?= $data['total_products']; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500/20 mr-4">
                        <i class="fas fa-table text-yellow-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Tables</p>
                        <p class="text-2xl font-bold text-white"><?= $data['total_tables']; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500/20 mr-4">
                        <i class="fas fa-store text-purple-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Branches</p>
                        <p class="text-2xl font-bold text-white"><?= $data['total_branches']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cashier Section for Branch Admins -->
        <?php if ($_SESSION['user_role'] === 'branch_admin'): ?>
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Cashier Interface</h2>
            <p class="text-gray-400 mb-6">Handle walk-in customers and table bookings</p>

            <form action="<?= BASEURL; ?>/admin/bookTable" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-white mb-3">Customer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-sm font-medium text-gray-300 mb-2">Customer Name *</label>
                                <input type="text" name="customer_name" id="customer_name" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter customer name" required>
                            </div>
                            <div>
                                <label for="customer_phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                                <input type="tel" name="customer_phone" id="customer_phone" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter phone number">
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div>
                        <label for="table_id" class="block text-sm font-medium text-gray-300 mb-2">Select Table *</label>
                        <select name="table_id" id="table_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="">Select a table</option>
                            <?php foreach($data['cashier_tables'] as $table): ?>
                                <option value="<?= $table->id ?>">Table #<?= $table->table_number ?> - <?= $table->branch_name ?> (Rp <?= number_format($table->price_per_hour, 0, ',', '.'); ?>/hr)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-300 mb-2">Date *</label>
                        <input type="date" name="date" id="date" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    </div>

                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-300 mb-2">Start Time *</label>
                        <input type="time" name="start_time" id="start_time" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-300 mb-2">Duration (hours) *</label>
                        <select name="duration" id="duration" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            <option value="1">1 hour</option>
                            <option value="2">2 hours</option>
                            <option value="3">3 hours</option>
                            <option value="4">4 hours</option>
                            <option value="5">5 hours</option>
                            <option value="6">6 hours</option>
                            <option value="7">7 hours</option>
                            <option value="8">8 hours</option>
                        </select>
                    </div>

                    <div>
                        <label for="promo_id" class="block text-sm font-medium text-gray-300 mb-2">Promo (Optional)</label>
                        <select name="promo_id" id="promo_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <option value="">No Promo</option>
                            <?php foreach($data['promos'] as $promo): ?>
                                <option value="<?= $promo->id ?>"><?= htmlspecialchars($promo->promo_name) ?> (<?= $promo->discount_percentage ?>% off)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                        Book Table & Process Payment
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Recent Activity and Active Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h2 class="text-xl font-bold text-white mb-4">Recent Bookings</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                            <tr>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Time</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['recent_bookings'] as $booking): ?>
                            <tr class="border-b border-gray-800 hover:bg-gray-850">
                                <td class="px-4 py-3 font-medium text-white"><?= htmlspecialchars($booking->customer_name ?? 'Walk-in'); ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars(date('M d, Y', strtotime($booking->start_time))); ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars(date('H:i', strtotime($booking->start_time)) . ' - ' . date('H:i', strtotime($booking->end_time))); ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        <?php
                                            if($booking->status == 'confirmed' || $booking->status == 'Paid'): echo 'bg-green-500/20 text-green-500';
                                            elseif($booking->status == 'pending' || $booking->status == 'Unpaid'): echo 'bg-yellow-500/20 text-yellow-500';
                                            else: echo 'bg-red-500/20 text-red-500';
                                            endif;
                                        ?>">
                                        <?= ucfirst($booking->status); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Tables Grid -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h2 class="text-xl font-bold text-white mb-4">Active Tables</h2>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                    <?php foreach($data['active_tables'] as $table): ?>
                    <div class="relative">
                        <div class="aspect-square bg-gray-800 rounded-lg border-2 flex items-center justify-center text-center p-2
                            <?php
                                if($table->table_status == 'available'): echo 'border-green-500';
                                elseif($table->table_status == 'occupied'): echo 'border-red-500';
                                else: echo 'border-yellow-500';
                                endif;
                            ?>">
                            <div>
                                <div class="font-bold text-white">#<?= $table->table_number; ?></div>
                                <div class="text-xs text-gray-400 mt-1"><?= $table->branch_name; ?></div>
                                <div class="text-xs mt-1
                                    <?php
                                        if($table->table_status == 'available'): echo 'text-green-500';
                                        elseif($table->table_status == 'occupied'): echo 'text-red-500';
                                        else: echo 'text-yellow-500';
                                        endif;
                                    ?>">
                                    <?= ucfirst($table->table_status); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Set today's date as default
document.getElementById('date').valueAsDate = new Date();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>