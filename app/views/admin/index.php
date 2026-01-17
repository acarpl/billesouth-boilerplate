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
            <!-- Monitoring Meja untuk Branch Admin -->
            <?php if ($_SESSION['user_role'] === 'branch_admin'): ?>
                <div class="p-8">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h1 class="text-3xl font-bold tracking-tighter uppercase text-white">Monitoring Meja</h1>
                            <?php
                            // Get branch name for display
                            $branch_name = 'Cabang Default';
                            if (isset($_SESSION['branch_id'])) {
                                $branch = $this->model('Branch_model')->getBranchById($_SESSION['branch_id']);
                                if ($branch) {
                                    $branch_name = $branch->branch_name;
                                }
                            }
                            ?>
                            <p class="text-gray-500 text-sm uppercase tracking-widest">Cabang: <?= htmlspecialchars($branch_name) ?></p>
                        </div>
                        <div class="flex gap-4">
                            <div class="bg-gray-900 border border-gray-800 p-4 rounded text-center min-w-[120px]">
                                <span class="block text-xs text-gray-500 uppercase">Total Meja</span>
                                <span class="text-2xl font-bold text-white"><?= count($data['tables']); ?></span>
                            </div>
                            <div class="bg-emerald-900/20 border border-emerald-500/50 p-4 rounded text-center min-w-[120px]">
                                <span class="block text-xs text-emerald-500 uppercase">Tersedia</span>
                                <span class="text-2xl font-bold text-emerald-500">
                                    <?php
                                    $count = 0;
                                    foreach ($data['tables'] as $t) if ($t->status == 'Available') $count++;
                                    echo $count;
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="min-h-screen bg-black pt-24 pb-10 px-6">
                        <div class="max-w-7xl mx-auto">

                            <!-- Header Dashboard -->
                            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-800 pb-8">
                                <div>
                                    <h1 class="text-4xl font-bold tracking-tighter text-white uppercase">Monitoring Billing</h1>
                                    <p class="text-gray-500 text-xs tracking-[0.3em] uppercase mt-2">Cabang: <?= htmlspecialchars($branch_name) ?></p>
                                </div>
                                <div class="text-right mt-4 md:mt-0">
                                    <p class="text-gray-500 text-[10px] uppercase">Waktu Server</p>
                                    <p class="text-xl font-mono font-bold text-white"><?= date('H:i'); ?> <span class="text-xs text-gray-600">WIB</span></p>
                                </div>
                            </div>

                            <!-- Grid Meja -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <?php foreach ($data['tables'] as $table) :
                                    $isActive = ($table->status == 'Occupied');
                                    $billing = $isActive ? $this->model('Billing_model')->getActiveBillingByTable($table->id) : null;
                                ?>
                                    <div class="relative group rounded-sm border transition-all duration-500 <?= $isActive ? 'bg-red-950/20 border-red-500/50' : 'bg-gray-900 border-gray-800' ?>">

                                        <div class="p-6">
                                            <div class="flex justify-between items-start mb-6">
                                                <div class="flex flex-col">
                                                    <span class="text-4xl font-black <?= $isActive ? 'text-red-500' : 'text-white/20' ?>"><?= $table->table_number ?></span>
                                                    <span class="text-[10px] text-gray-500 uppercase tracking-widest"><?= $table->type ?></span>
                                                </div>
                                                <div class="flex flex-col items-end">
                                                    <div class="w-3 h-3 rounded-full <?= $isActive ? 'bg-red-500 animate-ping' : 'bg-gray-700' ?>"></div>
                                                    <span class="text-[9px] mt-2 font-bold uppercase <?= $isActive ? 'text-red-500' : 'text-gray-500' ?>">
                                                        <?= $isActive ? 'In Use' : 'Ready' ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <?php if ($isActive && $billing) : ?>
                                                <!-- Bagian Sesi Aktif -->
                                                <div class="space-y-4 mb-8">
                                                    <div>
                                                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Durasi Bermain</p>
                                                        <div class="text-3xl font-mono font-bold text-white billing-timer"
                                                            data-start="<?= $billing->start_time ?>">
                                                            00:00:00
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-between text-[10px] text-gray-400 uppercase">
                                                        <span>Mulai: <?= date('H:i', strtotime($billing->start_time)) ?></span>
                                                        <span>Rate: <?= number_format($table->price_per_hour / 1000, 0) ?>k/h</span>
                                                    </div>
                                                </div>
                                                <a href="<?= BASEURL ?>/admin/stopBilling/<?= $table->id ?>"
                                                    onclick="return confirm('Selesaikan sesi dan hitung pembayaran?')"
                                                    class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-3 text-xs font-bold uppercase tracking-widest transition">
                                                    Selesaikan Sesi
                                                </a>
                                            <?php else : ?>
                                                <!-- Bagian Meja Kosong -->
                                                <div class="py-10 text-center">
                                                    <p class="text-gray-600 text-[10px] uppercase tracking-widest italic">Meja Tersedia</p>
                                                </div>
                                                <a href="<?= BASEURL ?>/admin/startBilling/<?= $table->id ?>"
                                                    class="block w-full bg-white hover:bg-gray-200 text-black text-center py-3 text-xs font-bold uppercase tracking-widest transition">
                                                    Mulai Sesi
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Dashboard untuk Super Admin -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white tracking-tight uppercase">BERANDA</h1>
                    <p class="text-gray-400">Selamat datang kembali, Admin Utama</p>
                </div>

                <!-- Branch Filter for Super Admin -->
                <?php if ($_SESSION['user_role'] === 'super_admin' && !empty($data['branches'])): ?>
                    <div class="mb-8 bg-gray-900 border border-gray-800 rounded-lg p-6 shadow">
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Pilih Cabang</label>
                            <select id="branch_filter"
                                class="bg-gray-800 border border-gray-700 rounded px-4 py-2 text-sm text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                <option value="all" <?= (!isset($_GET['branch_id']) || $_GET['branch_id'] == 'all') ? 'selected' : '' ?>>Semua Cabang</option>
                                <?php foreach ($data['branches'] as $branch): ?>
                                    <option value="<?= $branch->id ?>" <?= (isset($_GET['branch_id']) && $_GET['branch_id'] == $branch->id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($branch->branch_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button id="apply_branch_filter"
                                class="px-4 py-2 rounded bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs uppercase tracking-widest transition">
                                Terapkan
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <div class="p-6 bg-gray-900 border border-gray-800 rounded-lg shadow hover:-translate-y-1 hover:shadow-lg transition">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Total Meja</p>
                        <h2 class="text-4xl font-extrabold text-white"><?= count($data['tables'] ?? []) ?></h2>
                    </div>
                    <div class="p-6 bg-gray-900 border border-gray-800 rounded-lg shadow hover:-translate-y-1 hover:shadow-lg transition">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Pemesanan Aktif</p>
                        <h2 class="text-4xl font-extrabold text-white"><?= $data['active_bookings_count'] ?? 0 ?></h2>
                    </div>
                    <div class="p-6 bg-gray-900 border border-gray-800 rounded-lg shadow hover:-translate-y-1 hover:shadow-lg transition">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Pendapatan</p>
                        <h2 class="text-4xl font-extrabold text-emerald-400">Rp <?= number_format($data['total_revenue'] ?? 0, 0, ',', '.') ?></h2>
                    </div>
                    <div class="p-6 bg-gray-900 border border-gray-800 rounded-lg shadow hover:-translate-y-1 hover:shadow-lg transition">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Anggota Terdaftar</p>
                        <h2 class="text-4xl font-extrabold text-white"><?= $data['members_count'] ?? 0 ?></h2>
                    </div>
                </div>

                <!-- Tabel Pemesanan Terbaru -->
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-6 shadow">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold uppercase tracking-widest">Pemesanan Terbaru</h2>
                        <span class="text-xs text-gray-500"><?= date('d M Y') ?></span>
                    </div>

                    <?php if (!empty($data['recent_bookings'])): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-800">
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-gray-500">Kode</th>
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-gray-500">Pelanggan</th>
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-gray-500">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-gray-500">Jumlah</th>
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-gray-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800">
                                    <?php foreach ($data['recent_bookings'] as $booking): ?>
                                        <tr class="hover:bg-gray-800 transition">
                                            <td class="px-4 py-3"><?= htmlspecialchars($booking->booking_code) ?></td>
                                            <td class="px-4 py-3"><?= htmlspecialchars($booking->customer_name) ?></td>
                                            <td class="px-4 py-3"><?= date('d M Y', strtotime($booking->start_time)) ?></td>
                                            <td class="px-4 py-3">Rp <?= number_format($booking->total_price, 0, ',', '.') ?></td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wide
                        <?php if ($booking->payment_status === 'Paid'): ?>
                            bg-emerald-600 text-emerald-50
                        <?php elseif ($booking->payment_status === 'Unpaid'): ?>
                            bg-yellow-600 text-yellow-50
                        <?php else: ?>
                            bg-red-600 text-red-50
                        <?php endif; ?>">
                                                    <?= ucfirst($booking->payment_status) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-600 text-sm italic">Belum ada pemesanan terbaru.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <?php if ($_SESSION['user_role'] === 'branch_admin'): ?>
        <!-- JavaScript Timer Real-time -->
        <script>
            function updateTimers() {
                const timers = document.querySelectorAll('.billing-timer');
                timers.forEach(timer => {
                    const startTime = new Date(timer.dataset.start).getTime();
                    const now = new Date().getTime();
                    const diff = now - startTime;

                    if (diff < 0) return;

                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                    const hDisplay = hours < 10 ? "0" + hours : hours;
                    const mDisplay = minutes < 10 ? "0" + minutes : minutes;
                    const sDisplay = seconds < 10 ? "0" + seconds : seconds;

                    timer.innerText = hDisplay + ":" + mDisplay + ":" + sDisplay;
                });
            }

            // Update setiap detik
            setInterval(updateTimers, 1000);
            // Jalankan langsung saat page load
            updateTimers();
        </script>
    <?php else: ?>
        <script>
            // Handle branch filter for super admin
            document.addEventListener('DOMContentLoaded', function() {
                const branchFilter = document.getElementById('branch_filter');
                const applyButton = document.getElementById('apply_branch_filter');

                if (branchFilter && applyButton) {
                    applyButton.addEventListener('click', function() {
                        const selectedBranchId = branchFilter.value;
                        if (selectedBranchId === 'all' || selectedBranchId === '') {
                            // If "all" is selected or empty, reload with no specific branch (shows all)
                            window.location.href = window.location.origin + window.location.pathname + '?branch_id=all';
                        } else {
                            // Redirect to the same page with the selected branch ID
                            window.location.href = window.location.origin + window.location.pathname + '?branch_id=' + selectedBranchId;
                        }
                    });

                    // Automatically apply filter when branch is selected from dropdown
                    branchFilter.addEventListener('change', function() {
                        const selectedBranchId = branchFilter.value;
                        if (selectedBranchId === 'all' || selectedBranchId === '') {
                            // If "all" is selected or empty, reload with no specific branch (shows all)
                            window.location.href = window.location.origin + window.location.pathname + '?branch_id=all';
                        } else {
                            // Redirect to the same page with the selected branch ID
                            window.location.href = window.location.origin + window.location.pathname + '?branch_id=' + selectedBranchId;
                        }
                    });
                }
            });
        </script>
    <?php endif; ?>
</body>

</html>