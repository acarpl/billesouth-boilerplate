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
        <main class="flex-1 p-8 bg-gray-950 min-h-screen ml-64">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Detail Billing</h1>
                    <p class="text-gray-500 text-sm uppercase tracking-widest mt-1">Informasi lengkap tentang sesi billing</p>
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

            <!-- Back Button -->
            <div class="mb-6">
                <a href="<?= BASEURL ?>/admin/billing" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Billing
                </a>
            </div>

            <!-- Billing Detail Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-xl">
                <!-- Billing Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 pb-6 border-b border-gray-800">
                    <div>
                        <h2 class="text-2xl font-bold text-white mb-1">No. Billing: <span class="text-blue-400"><?= $data['billing']->billing_number ?></span></h2>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                <?php if ($data['billing']->status === 'Active'): ?>
                                    bg-emerald-600/20 text-emerald-500 border border-emerald-500
                                <?php elseif ($data['billing']->status === 'Finished'): ?>
                                    bg-blue-600/20 text-blue-500 border border-blue-500
                                <?php else: ?>
                                    bg-red-600/20 text-red-500 border border-red-500
                                <?php endif; ?>">
                                <?= $data['billing']->status ?>
                            </span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                <?php if ($data['billing']->payment_status === 'Paid'): ?>
                                    bg-green-600/20 text-green-500 border border-green-500
                                <?php else: ?>
                                    bg-yellow-600/20 text-yellow-500 border border-yellow-500
                                <?php endif; ?>">
                                <?= $data['billing']->payment_status ?>
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 text-right">
                        <p class="text-3xl font-black text-emerald-500">Rp <?= number_format($data['billing']->grand_total, 0, ',', '.') ?></p>
                        <p class="text-gray-500 text-sm uppercase tracking-widest mt-1">Total Pembayaran</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column: Basic Information -->
                    <div class="space-y-6">
                        <!-- Location & Table Info -->
                        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-blue-500"></i> Lokasi & Meja
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Cabang</p>
                                    <p class="text-white font-bold"><?= $data['branch']->branch_name ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Meja</p>
                                    <p class="text-white font-bold">#<?= $data['table']->table_number ?> (<?= strtoupper($data['table']->type) ?>)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Timing Information -->
                        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-clock text-blue-500"></i> Waktu Sesi
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Waktu Mulai</p>
                                    <p class="text-white font-bold"><?= date('d M Y H:i', strtotime($data['billing']->start_time)) ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Waktu Selesai</p>
                                    <p class="text-white font-bold"><?= $data['billing']->end_time ? date('d M Y H:i', strtotime($data['billing']->end_time)) : '-' ?></p>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Durasi Tipe</p>
                                    <p class="text-white font-bold"><?= $data['billing']->duration_type ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Total Waktu</p>
                                    <p class="text-white font-bold"><?= $data['billing']->total_time_minutes ?> menit</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-500"></i> Informasi Tambahan
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Dibuat pada</p>
                                    <p class="text-white font-bold"><?= date('d M Y H:i', strtotime($data['billing']->created_at)) ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">User ID</p>
                                    <p class="text-white font-bold"><?= $data['billing']->user_id ?: '-' ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Booking ID</p>
                                    <p class="text-white font-bold"><?= $data['billing']->booking_id ?: '-' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Cost Breakdown -->
                    <div class="space-y-6">
                        <!-- Cost Breakdown -->
                        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-receipt text-blue-500"></i> Rincian Pembayaran
                            </h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <p class="text-gray-400">Biaya Meja</p>
                                    <p class="text-white font-bold">Rp <?= number_format($data['billing']->table_cost, 0, ',', '.') ?></p>
                                </div>

                                <div class="flex justify-between">
                                    <p class="text-gray-400">Biaya Tambahan</p>
                                    <p class="text-white font-bold">Rp <?= number_format($data['billing']->additional_cost, 0, ',', '.') ?></p>
                                </div>

                                <div class="border-t border-gray-800 my-3 pt-3">
                                    <div class="flex justify-between">
                                        <p class="text-gray-300 font-bold">Subtotal</p>
                                        <p class="text-white font-bold">Rp <?= number_format($data['billing']->table_cost + $data['billing']->additional_cost, 0, ',', '.') ?></p>
                                    </div>
                                </div>

                                <div class="border-t-2 border-emerald-500/30 mt-3 pt-3">
                                    <div class="flex justify-between">
                                        <p class="text-emerald-400 font-bold text-lg">Total Pembayaran</p>
                                        <p class="text-emerald-400 font-black text-xl">Rp <?= number_format($data['billing']->grand_total, 0, ',', '.') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Information -->
                        <?php if (isset($data['user']) && $data['user']): ?>
                        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-user text-blue-500"></i> Informasi Pengguna
                            </h3>

                            <div>
                                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1">Nama</p>
                                <p class="text-white font-bold"><?= htmlspecialchars($data['user']->name ?? '') ?></p>

                                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1 mt-3">Email</p>
                                <p class="text-white font-bold"><?= htmlspecialchars($data['user']->email ?? '') ?></p>

                                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-1 mt-3">Telepon</p>
                                <p class="text-white font-bold"><?= htmlspecialchars($data['user']->phone ?? '') ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-cogs text-blue-500"></i> Aksi
                            </h3>

                            <div class="flex flex-wrap gap-3">
                                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center">
                                    <i class="fas fa-print mr-2"></i> Cetak Struk
                                </button>
                                <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition flex items-center">
                                    <i class="fas fa-download mr-2"></i> Export PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>

</html>