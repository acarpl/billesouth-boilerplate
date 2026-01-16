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
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-white">
    <div class="flex">
        <!-- Sidebar -->
        <?php $this->view('templates/admin_sidebar'); ?>

        <!-- Main Content -->
        <main class="flex-1 p-8 bg-gray-950 min-h-screen">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Dasbor</h1>
                <p class="text-gray-400">Selamat datang kembali, Admin!</p>
            </div>


            <!-- Cashier Section for Branch Admins -->
            <?php if ($_SESSION['user_role'] === 'branch_admin'): ?>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

                    <!-- Cashier Form -->
                    <div class="lg:col-span-2 bg-gray-900 rounded-lg p-6 border border-gray-800">
                        <h2 class="text-xl font-bold text-white mb-4">Antarmuka Kasir</h2>
                        <p class="text-gray-400 mb-6">Tangani pelanggan datang langsung dan pemesanan meja</p>

                        <form id="cashierForm" action="<?= BASEURL; ?>/admin/bookTable" method="POST">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Customer Information -->
                                <div class="md:col-span-2">
                                    <h3 class="text-lg font-medium text-white mb-3">Informasi Pelanggan</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="customer_name" class="block text-sm font-medium text-gray-300 mb-2">Nama Pelanggan *</label>
                                            <input type="text" name="customer_name" id="customer_name" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan nama pelanggan" required>
                                        </div>
                                        <div>
                                            <label for="customer_phone" class="block text-sm font-medium text-gray-300 mb-2">Nomor Telepon</label>
                                            <input type="tel" name="customer_phone" id="customer_phone" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan nomor telepon">
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Details -->
                                <div>
                                    <label for="table_id" class="block text-sm font-medium text-gray-300 mb-2">Pilih Meja *</label>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 max-h-60 overflow-y-auto p-2 bg-gray-700 rounded-lg">
                                        <?php
                                        // Create an array of booked table IDs for quick lookup
                                        $booked_table_ids = [];
                                        foreach ($data['active_bookings'] as $booking) {
                                            $booked_table_ids[] = $booking->table_id;
                                        }

                                        foreach ($data['cashier_tables'] as $table):
                                            $is_booked = in_array($table->id, $booked_table_ids);
                                            $table_type_lower = strtolower($table->type);

                                            // Determine classes based on table type and availability
                                            if ($table_type_lower === 'vvip') {
                                                if ($is_booked) {
                                                    $box_classes = "border-2 border-purple-500 bg-gray-800"; // VVIP booked: purple outline only
                                                } else {
                                                    $box_classes = "bg-purple-600 text-white"; // VVIP available: purple fill
                                                }
                                            } elseif ($table_type_lower === 'vip') {
                                                if ($is_booked) {
                                                    $box_classes = "border-2 border-yellow-400 bg-gray-800"; // VIP booked: yellow outline only
                                                } else {
                                                    $box_classes = "bg-yellow-500 text-gray-900"; // VIP available: yellow fill
                                                }
                                            } else {
                                                if ($is_booked) {
                                                    $box_classes = "border-2 border-green-500 bg-gray-800"; // Regular booked: green outline only
                                                } else {
                                                    $box_classes = "bg-green-500 text-gray-900"; // Regular available: green fill
                                                }
                                            }
                                        ?>
                                            <div
                                                class="flex flex-col items-center justify-center p-2 rounded-lg cursor-pointer <?= $box_classes ?>"
                                                onclick="selectTable(<?= $table->id ?>, <?= $table->price_per_hour ?>)"
                                                data-table-id="<?= $table->id ?>"
                                                data-price="<?= $table->price_per_hour ?>">
                                                <div class="font-bold">#<?= $table->table_number ?></div>
                                                <div class="text-xs mt-1"><?= ucfirst($table->type) ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <!-- Hidden input to store selected table ID -->
                                    <input type="hidden" name="table_id" id="table_id" required>
                                </div>

                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal *</label>
                                    <input type="date" name="date" id="date" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                </div>

                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-300 mb-2">Waktu Mulai *</label>
                                    <input type="time" name="start_time" id="start_time" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                </div>

                                <div>
                                    <label for="duration" class="block text-sm font-medium text-gray-300 mb-2">Durasi (jam) *</label>
                                    <select name="duration" id="duration" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                        <option value="1">1 jam</option>
                                        <option value="2">2 jam</option>
                                        <option value="3">3 jam</option>
                                        <option value="4">4 jam</option>
                                        <option value="5">5 jam</option>
                                        <option value="6">6 jam</option>
                                        <option value="7">7 jam</option>
                                        <option value="8">8 jam</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="promo_id" class="block text-sm font-medium text-gray-300 mb-2">Promo (Opsional)</label>
                                    <select name="promo_id" id="promo_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                        <option value="">Tanpa Promo</option>
                                        <?php foreach ($data['promos'] as $promo): ?>
                                            <option value="<?= $promo->id ?>" data-discount-type="<?= $promo->discount_type ?>" data-discount-value="<?= $promo->discount_value ?>"><?= htmlspecialchars($promo->code) ?> (<?php
                                                                                                                                                                                                                            if ($promo->discount_type === 'percentage') {
                                                                                                                                                                                                                                echo $promo->discount_value . '% diskon';
                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                echo 'Rp ' . number_format($promo->discount_value, 0, ',', '.') . ' diskon';
                                                                                                                                                                                                                            }
                                                                                                                                                                                                                            ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                                    Pesan Meja & Proses Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1 bg-gray-900 rounded-lg p-6 border border-gray-800">
                        <h2 class="text-xl font-bold text-white mb-4">Ringkasan Pesanan</h2>

                        <div class="space-y-4">
                            <!-- Order Summary -->
                            <div class="mt-6 bg-gray-800 rounded-lg p-4 border border-gray-700">
                                <h3 class="text-lg font-medium text-white mb-3">Ringkasan Pesanan</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-gray-400">Harga Meja/Jam:</p>
                                        <p class="text-white font-medium" id="table-price">Rp 0</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400">Durasi:</p>
                                        <p class="text-white font-medium" id="duration-text">0 jam</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400">Subtotal:</p>
                                        <p class="text-white font-medium" id="subtotal">Rp 0</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400">Diskon:</p>
                                        <p class="text-white font-medium" id="discount">Rp 0</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-gray-400">Total:</p>
                                        <p class="text-white font-bold text-xl" id="total">Rp 0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="text-gray-400 mb-2">Total Meja</div>
                    <div class="text-3xl font-bold text-white"><?php echo count($data['tables']); ?></div>
                </div>
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="text-gray-400 mb-2">Pemesanan Aktif</div>
                    <div class="text-3xl font-bold text-white"><?php echo $data['active_bookings_count']; ?></div>
                </div>
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="text-gray-400 mb-2">Total Pendapatan</div>
                    <div class="text-3xl font-bold text-white">Rp <?php echo number_format($data['total_revenue'], 0, ',', '.'); ?></div>
                </div>
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="text-gray-400 mb-2">Anggota</div>
                    <div class="text-3xl font-bold text-white"><?php echo $data['members_count']; ?></div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <h2 class="text-xl font-bold text-white mb-4">Pemesanan Terbaru</h2>
                <div class="overflow-x-auto">
                    <?php if (!empty($data['recent_bookings'])): ?>
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID Pemesanan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Jumlah</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <?php foreach ($data['recent_bookings'] as $booking): ?>
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-white"><?= htmlspecialchars($booking->booking_code); ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-white"><?= htmlspecialchars($booking->customer_name); ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-white"><?= date('M j, Y', strtotime($booking->start_time)); ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-white">Rp <?= number_format($booking->total_price, 0, ',', '.'); ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?php if ($booking->payment_status === 'Paid'): ?>
                                        bg-green-800 text-green-200
                                    <?php elseif ($booking->payment_status === 'Unpaid'): ?>
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
                        <p class="text-gray-400">Tidak ada pemesanan terbaru ditemukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <script>
        // Function to handle table selection
        function selectTable(tableId, pricePerHour) {
            // Update hidden input with selected table ID
            document.getElementById('table_id').value = tableId;

            // Update price display
            document.getElementById('table-price').textContent = 'Rp ' + pricePerHour.toLocaleString('id-ID');

            // Clear previous selections (remove selected class if we had one)
            const tableBoxes = document.querySelectorAll('[data-table-id]');
            tableBoxes.forEach(box => {
                // Reset to original appearance based on availability and type
                const boxTableId = parseInt(box.getAttribute('data-table-id'));
                const boxPrice = parseFloat(box.getAttribute('data-price'));

                // Find the corresponding table object to determine its type and availability
                // This would require passing table data to JavaScript, so we'll use data attributes
                // which are already set in the HTML

                // For now, we'll just highlight the selected table with a different style
                if (boxTableId === tableId) {
                    // Add a selection indicator - we'll add a thicker border
                    box.style.border = '3px solid #3b82f6'; // Blue border for selected
                    box.style.transform = 'scale(1.05)';
                } else {
                    // Reset other boxes to their original state
                    box.style.border = '';
                    box.style.transform = '';
                }
            });

            // Update order summary if duration is selected
            updateOrderSummary();
        }

        // Function to update order summary
        function updateOrderSummary() {
            const tablePrice = parseFloat(document.getElementById('table-price').textContent.replace(/[^\d]/g, '')) || 0;
            const duration = parseInt(document.getElementById('duration').value) || 0;
            const promoElement = document.getElementById('promo_id').selectedOptions[0];

            let discountAmount = 0;
            if (promoElement && promoElement.value !== '') {
                const discountType = promoElement.getAttribute('data-discount-type');
                const discountValue = parseFloat(promoElement.getAttribute('data-discount-value')) || 0;

                if (discountType === 'percentage') {
                    discountAmount = (tablePrice * duration * discountValue) / 100;
                } else { // fixed amount
                    discountAmount = discountValue;
                }

                // Make sure discount doesn't exceed subtotal
                discountAmount = Math.min(discountAmount, tablePrice * duration);
            }

            const subtotal = tablePrice * duration;
            const total = subtotal - discountAmount;

            document.getElementById('duration-text').textContent = duration + ' hours';
            document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('discount').textContent = 'Rp ' + discountAmount.toLocaleString('id-ID');
            document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Add event listeners for duration and promo changes
        document.getElementById('duration').addEventListener('change', updateOrderSummary);
        document.getElementById('promo_id').addEventListener('change', updateOrderSummary);

        // Initialize today's date for the date picker
        document.getElementById('date').valueAsDate = new Date();
    </script>
</body>

</html>