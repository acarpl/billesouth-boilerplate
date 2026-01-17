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
        <main class="flex-1 p-8 bg-[#030712] min-h-screen text-white">
            <!-- Page Title -->
            <header class="mb-10">
                <h1 class="text-3xl font-extrabold tracking-wide uppercase">Detail Billing</h1>
                <p class="text-gray-400 text-xs mt-1 tracking-widest uppercase">
                    Informasi lengkap tentang sesi billing
                </p>
            </header>

            <!-- Back Button -->
            <div class="mb-6">
                <a href="<?= BASEURL ?>/admin/billing" class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Billing
                </a>
            </div>

            <!-- Billing Detail Card -->
            <div class="bg-[#0A0F1C] border border-gray-800 rounded-xl p-6 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Billing Information -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-bold uppercase tracking-widest border-b border-gray-700 pb-2">Informasi Billing</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">No Billing</p>
                                <p class="font-semibold"><?= $data['billing']->billing_number ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Status</p>
                                <p>
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest
                                    <?php if ($data['billing']->status === 'Active'): ?>
                                        bg-emerald-600 text-emerald-50
                                    <?php elseif ($data['billing']->status === 'Finished'): ?>
                                        bg-blue-600 text-blue-50
                                    <?php else: ?>
                                        bg-red-600 text-red-50
                                    <?php endif; ?>">
                                        <?= $data['billing']->status ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Cabang</p>
                                <p class="font-semibold"><?= $data['branch']->branch_name ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Meja</p>
                                <p class="font-semibold"><?= $data['table']->table_number ?> (<?= strtoupper($data['table']->type) ?>)</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Durasi Tipe</p>
                                <p class="font-semibold"><?= $data['billing']->duration_type ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Status Pembayaran</p>
                                <p>
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest
                                    <?php if ($data['billing']->payment_status === 'Paid'): ?>
                                        bg-green-600 text-green-50
                                    <?php else: ?>
                                        bg-yellow-600 text-yellow-50
                                    <?php endif; ?>">
                                        <?= $data['billing']->payment_status ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timing Information -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-bold uppercase tracking-widest border-b border-gray-700 pb-2">Waktu & Biaya</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Waktu Mulai</p>
                                <p class="font-semibold"><?= date('d M Y H:i', strtotime($data['billing']->start_time)) ?></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Waktu Selesai</p>
                                <p class="font-semibold"><?= $data['billing']->end_time ? date('d M Y H:i', strtotime($data['billing']->end_time)) : '-' ?></p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Total Waktu (menit)</p>
                                <p class="font-semibold"><?= $data['billing']->total_time_minutes ?> menit</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Durasi Tipe</p>
                                <p class="font-semibold"><?= $data['billing']->duration_type ?></p>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Biaya Meja</p>
                                <p class="font-semibold">Rp <?= number_format($data['billing']->table_cost, 0, ',', '.') ?></p>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-gray-400 text-xs uppercase tracking-widest">Biaya Tambahan</p>
                                <p class="font-semibold">Rp <?= number_format($data['billing']->additional_cost, 0, ',', '.') ?></p>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-700">
                                <p class="text-gray-300 text-sm font-bold uppercase tracking-widest">Total Pembayaran</p>
                                <p class="font-bold text-lg text-emerald-400">Rp <?= number_format($data['billing']->grand_total, 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information -->
                <div class="mt-8">
                    <h2 class="text-xl font-bold uppercase tracking-widest border-b border-gray-700 pb-2">Informasi Tambahan</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <p class="text-gray-400 text-xs uppercase tracking-widest">Dibuat pada</p>
                            <p class="font-semibold"><?= date('d M Y H:i', strtotime($data['billing']->created_at)) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs uppercase tracking-widest">User ID</p>
                            <p class="font-semibold"><?= $data['billing']->user_id ?: '-' ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs uppercase tracking-widest">Booking ID</p>
                            <p class="font-semibold"><?= $data['billing']->booking_id ?: '-' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>

</html>