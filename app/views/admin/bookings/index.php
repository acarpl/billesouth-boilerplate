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
    <main class="flex-1 p-8 bg-gray-950 min-h-screen ml-64">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Manajemen Reservasi</h1>
            <p class="text-gray-400">Kelola status pesanan dan cetak bukti pembayaran</p>
        </div>
        <!-- Tombol Refresh -->
        <a href="<?= BASEURL ?>/admin/bookings" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-sync-alt"></i>
        </a>
    </div>

    <!-- Tabel Reservasi -->
    <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden shadow-2xl">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-800/50 text-gray-400 text-[10px] font-bold uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Pelanggan</th>
                    <th class="px-6 py-4">Meja</th>
                    <th class="px-6 py-4">Jadwal Main</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi Manual (ON/OFF)</th>
                    <th class="px-6 py-4 text-right">Invoice</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                <?php foreach($bookings as $booking): ?>
                <tr class="hover:bg-white/[0.02] transition">
                    <td class="px-6 py-4 font-mono text-sm text-blue-400">#<?= $booking->booking_code; ?></td>
                    <td class="px-6 py-4">
                        <div class="text-white font-bold text-sm"><?= htmlspecialchars($booking->user_name ?? ''); ?></div>
                        <div class="text-[10px] text-gray-500"><?= $booking->phone ?? '-'; ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-white font-bold">#<?= $booking->table_number; ?></span>
                        <span class="text-[10px] block text-gray-500 uppercase"><?= $booking->type ?? 'REGULAR'; ?></span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="text-gray-300"><?= date('d M Y', strtotime($booking->start_time)); ?></div>
                        <div class="text-xs text-gray-500"><?= date('H:i', strtotime($booking->start_time)); ?> - <?= date('H:i', strtotime($booking->end_time)); ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-[10px] font-bold rounded-full border 
                            <?= ($booking->payment_status == 'Paid') ? 'bg-emerald-500/10 border-emerald-500 text-emerald-500' : 
                               (($booking->payment_status == 'Unpaid') ? 'bg-amber-500/10 border-amber-500 text-amber-500' : 'bg-red-500/10 border-red-500 text-red-500') ?>">
                            <?= strtoupper($booking->payment_status); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <?php if($booking->payment_status != 'Paid'): ?>
                                <!-- Tombol ON (Approve/Bayar) -->
                                <a href="<?= BASEURL ?>/admin/updateBookingStatus/<?= $booking->booking_code ?>/Paid" 
                                   class="p-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition" title="Tandai Sudah Bayar">
                                    <i class="fas fa-check"></i>
                                </a>
                            <?php else: ?>
                                <!-- Tombol OFF (Batalkan/Unpaid) -->
                                <a href="<?= BASEURL ?>/admin/updateBookingStatus/<?= $booking->booking_code ?>/Unpaid" 
                                   class="p-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition" title="Tandai Belum Bayar">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                            
                            <a href="<?= BASEURL ?>/admin/updateBookingStatus/<?= $booking->booking_code ?>/Cancelled" 
                               onclick="return confirm('Batalkan pesanan ini?')"
                               class="p-2 bg-red-600/20 hover:bg-red-600 text-red-500 hover:text-white rounded-lg transition" title="Cancel Booking">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <?php if($booking->payment_status == 'Paid'): ?>
                            <a href="<?= BASEURL ?>/admin/invoice/<?= $booking->booking_code ?>" target="_blank" 
                               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-bold transition">
                                <i class="fas fa-print"></i> INVOICE
                            </a>
                        <?php else: ?>
                            <span class="text-[10px] text-gray-600 italic">Menunggu Bayar</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>