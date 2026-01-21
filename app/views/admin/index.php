<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?> | Bille Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #111827;
        }

        ::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 10px;
        }

        .table-card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-[#0b0f1a] text-gray-200">
    <div class="flex min-h-screen">
        <!-- Fixed Sidebar is loaded separately -->
        <?php $this->view('templates/admin_sidebar'); ?>

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-10 overflow-y-auto ml-64">

            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Dasbor Utama</h1>
                    <p class="text-gray-500 text-sm uppercase tracking-widest mt-1">Cabang: Citra Raya Tangerang</p>
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

            <!-- Kartu Statistik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute right-[-10%] top-[-10%] text-white/5 text-6xl group-hover:scale-110 transition-transform"><i class="fa-solid fa-table-cells"></i></div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Meja</p>
                    <h3 class="text-3xl font-bold text-white"><?= count($data['tables']); ?></h3>
                </div>
                <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute right-[-10%] top-[-10%] text-white/5 text-6xl group-hover:scale-110 transition-transform"><i class="fa-solid fa-stopwatch"></i></div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Pemesanan Aktif</p>
                    <h3 class="text-3xl font-bold text-blue-500"><?= $data['active_bookings_count']; ?></h3>
                </div>
                <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute right-[-10%] top-[-10%] text-white/5 text-6xl group-hover:scale-110 transition-transform"><i class="fa-solid fa-money-bill-trend-up"></i></div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Pendapatan</p>
                    <h3 class="text-2xl font-bold text-emerald-500">Rp <?= number_format($data['total_revenue'], 0, ',', '.'); ?></h3>
                </div>
                <div class="bg-gray-900 border border-gray-800 p-6 rounded-2xl relative overflow-hidden group">
                    <div class="absolute right-[-10%] top-[-10%] text-white/5 text-6xl group-hover:scale-110 transition-transform"><i class="fa-solid fa-users"></i></div>
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Member</p>
                    <h3 class="text-3xl font-bold text-white"><?= $data['members_count']; ?></h3>
                </div>
            </div>

            <!-- Interface Kasir -->
            <?php if ($_SESSION['user_role'] === 'branch_admin'): ?>
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 mb-10">

                    <!-- Form Booking -->
                    <div class="xl:col-span-8 bg-gray-900/50 border border-gray-800 rounded-3xl p-8 glass">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 bg-blue-600/20 text-blue-500 rounded-lg flex items-center justify-center"><i class="fa-solid fa-cash-register"></i></div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Antarmuka Kasir</h2>
                                <p class="text-xs text-gray-500 uppercase tracking-widest">Input Walk-in & Reservasi</p>
                            </div>
                        </div>

                        <form id="cashierForm" action="<?= BASEURL; ?>/admin/bookTable" method="POST" class="space-y-8">
                            <!-- Section: Data Pelanggan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2 tracking-widest">Nama Pelanggan *</label>
                                    <input type="text" name="customer_name" class="w-full bg-black/50 border border-gray-800 rounded-xl p-3 text-white focus:border-blue-500 outline-none transition-all" placeholder="Contoh: Akmal" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2 tracking-widest">Nomor Telepon</label>
                                    <input type="tel" name="customer_phone" class="w-full bg-black/50 border border-gray-800 rounded-xl p-3 text-white focus:border-blue-500 outline-none transition-all" placeholder="0812xxxx">
                                </div>
                            </div>

                            <!-- Section: Denah Meja -->
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-4 tracking-widest text-center">Pilih Meja Tersedia</label>
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4 max-h-[300px] overflow-y-auto p-4 bg-black/40 rounded-2xl border border-gray-800">
                                    <?php
                                    $booked_table_ids = array_map(fn($b) => $b->table_id, $data['active_bookings']);

                                    foreach ($data['cashier_tables'] as $table):
                                        $is_booked = in_array($table->id, $booked_table_ids);
                                        $type = strtolower($table->type);

                                        // Styling Logic
                                        $colorClass = "border-gray-700 bg-gray-800 text-gray-400"; // Default
                                        if (!$is_booked) {
                                            if ($type === 'vvip') $colorClass = "border-purple-600/50 bg-purple-600/10 text-purple-400 hover:bg-purple-600 hover:text-white";
                                            elseif ($type === 'vip') $colorClass = "border-yellow-600/50 bg-yellow-600/10 text-yellow-500 hover:bg-yellow-600 hover:text-black";
                                            else $colorClass = "border-emerald-600/50 bg-emerald-600/10 text-emerald-500 hover:bg-emerald-600 hover:text-black";
                                        } else {
                                            $colorClass = "border-red-900/50 bg-red-900/20 text-red-800 opacity-50 cursor-not-allowed";
                                        }
                                    ?>
                                        <div onclick="<?= $is_booked ? '' : "selectTable(this, {$table->id}, {$table->price_per_hour})" ?>"
                                            class="table-card flex flex-col items-center justify-center p-3 rounded-xl border transition-all cursor-pointer <?= $colorClass ?>"
                                            data-id="<?= $table->id ?>" data-price="<?= $table->price_per_hour ?>">
                                            <span class="text-lg font-black">#<?= $table->table_number ?></span>
                                            <span class="text-[8px] font-bold uppercase"><?= $table->type ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <input type="hidden" name="table_id" id="table_id" required>
                            </div>

                            <!-- Section: Waktu & Promo -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2 tracking-widest">Tanggal</label>
                                    <input type="date" name="date" id="date" class="w-full bg-black/50 border border-gray-800 rounded-xl p-3 text-white outline-none focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2 tracking-widest">Jam Mulai</label>
                                    <input type="time" name="start_time" id="start_time" class="w-full bg-black/50 border border-gray-800 rounded-xl p-3 text-white outline-none focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2 tracking-widest">Durasi</label>
                                    <select name="duration" id="duration" class="w-full bg-black/50 border border-gray-800 rounded-xl p-3 text-white outline-none focus:border-blue-500" onchange="updateOrderSummary()">
                                        <?php for ($i = 1; $i <= 8; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?> Jam</option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-center gap-6 pt-6 border-t border-gray-800">
                                <div class="w-full sm:w-1/2">
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-2 tracking-widest">Gunakan Promo</label>
                                    <select name="promo_id" id="promo_id" class="w-full bg-black/50 border border-gray-800 rounded-xl p-3 text-white outline-none focus:border-blue-500" onchange="updateOrderSummary()">
                                        <option value="">Tanpa Promo</option>
                                        <?php foreach ($data['promos'] as $promo): ?>
                                            <option value="<?= $promo->id ?>" data-type="<?= $promo->discount_type ?>" data-val="<?= $promo->discount_value ?>">
                                                <?= $promo->code ?> (<?= $promo->discount_type == 'percentage' ? $promo->discount_value . '%' : 'Rp ' . number_format($promo->discount_value, 0, ',', '.') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-2xl shadow-lg shadow-blue-900/20 transition-all">
                                    Proses Billing <i class="fa-solid fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Sidebar Summary -->
                    <div class="xl:col-span-4 space-y-6">
                        <div class="bg-gradient-to-br from-blue-900/20 to-purple-900/20 border border-blue-500/20 rounded-3xl p-8 sticky top-6">
                            <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                                <i class="fa-solid fa-receipt text-blue-500"></i> Ringkasan
                            </h2>
                            <div class="space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Harga Meja/Jam</span>
                                    <span id="display-price" class="text-white font-bold">Rp 0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400">Durasi</span>
                                    <span id="display-duration" class="text-white font-bold">0 Jam</span>
                                </div>
                                <div class="border-t border-gray-800 my-4 pt-4">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-400">Subtotal</span>
                                        <span id="display-subtotal" class="text-white font-bold">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-400">Diskon</span>
                                        <span id="display-discount" class="text-red-400 font-bold">- Rp 0</span>
                                    </div>
                                </div>
                                <div class="bg-black/40 p-4 rounded-2xl border border-gray-800 mt-6">
                                    <p class="text-[10px] font-bold text-gray-500 uppercase mb-1 tracking-[0.2em]">Total Pembayaran</p>
                                    <p id="display-total" class="text-3xl font-black text-white">Rp 0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tabel Pemesanan Terbaru -->
            <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl font-bold text-white">Aktivitas Pemesanan</h2>
                    <button class="text-xs font-bold text-blue-500 hover:text-blue-400 uppercase tracking-widest">Lihat Semua</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.2em] border-b border-gray-800">
                                <th class="pb-4">Kode</th>
                                <th class="pb-4">Pelanggan</th>
                                <th class="pb-4">Tanggal</th>
                                <th class="pb-4">Nominal</th>
                                <th class="pb-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <?php foreach ($data['recent_bookings'] as $booking): ?>
                                <tr class="group hover:bg-white/[0.02] transition-all">
                                    <td class="py-4 font-mono text-sm text-blue-400">#<?= $booking->booking_code ?></td>
                                    <td class="py-4 text-sm font-bold text-white"><?= $booking->user_name ?></td>
                                    <td class="py-4 text-sm text-gray-400"><?= date('d M Y', strtotime($booking->start_time)) ?></td>
                                    <td class="py-4 text-sm font-bold text-emerald-500">Rp <?= number_format($booking->total_price, 0, ',', '.') ?></td>
                                    <td class="py-4">
                                        <span class="px-3 py-1 text-[10px] font-bold rounded-full border
                                            <?= $booking->payment_status === 'Paid' ? 'bg-emerald-500/10 border-emerald-500 text-emerald-500' : ($booking->payment_status === 'Unpaid' ? 'bg-amber-500/10 border-amber-500 text-amber-500' : 'bg-red-500/10 border-red-500 text-red-500') ?>">
                                            <?= strtoupper($booking->payment_status) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentPricePerHour = 0;

        function selectTable(element, tableId, pricePerHour) {
            currentPricePerHour = pricePerHour;
            document.getElementById('table_id').value = tableId;

            // Visual Reset & Select
            document.querySelectorAll('.table-card').forEach(card => {
                card.classList.remove('ring-4', 'ring-blue-500', 'scale-105', 'z-10');
            });
            element.classList.add('ring-4', 'ring-blue-500', 'scale-105', 'z-10');

            updateOrderSummary();
        }

        function updateOrderSummary() {
            const duration = parseInt(document.getElementById('duration').value);
            const promo = document.getElementById('promo_id').selectedOptions[0];

            let subtotal = currentPricePerHour * duration;
            let discount = 0;

            if (promo && promo.value !== '') {
                const type = promo.getAttribute('data-type');
                const val = parseFloat(promo.getAttribute('data-val'));
                discount = (type === 'percentage') ? (subtotal * val / 100) : val;
            }

            const total = subtotal - discount;

            // Update UI
            document.getElementById('display-price').innerText = 'Rp ' + currentPricePerHour.toLocaleString('id-ID');
            document.getElementById('display-duration').innerText = duration + ' Jam';
            document.getElementById('display-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('display-discount').innerText = '- Rp ' + discount.toLocaleString('id-ID');
            document.getElementById('display-total').innerText = 'Rp ' + (total > 0 ? total : 0).toLocaleString('id-ID');
        }

        // Init
        document.getElementById('date').valueAsDate = new Date();
    </script>
</body>

</html>