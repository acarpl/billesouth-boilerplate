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
            <h1 class="text-3xl font-bold text-white">Manajemen Pesanan</h1>
            <p class="text-gray-400">Kelola pesanan merchandise dan statusnya</p>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                <?php echo $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>

        <!-- Orders Table -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                    <tr>
                        <th class="px-4 py-3">ID Pesanan</th>
                        <th class="px-4 py-3">Pelanggan</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Metode Pembayaran</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr class="border-b border-gray-800 hover:bg-gray-850">
                        <td class="px-4 py-3 font-medium text-white">#<?= $order->id; ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($order->customer_name ?? 'Guest'); ?></td>
                        <td class="px-4 py-3"><?= isset($order->created_at) && !empty($order->created_at) ? date('M d, Y H:i', strtotime($order->created_at)) : 'N/A'; ?></td>
                        <td class="px-4 py-3">Rp <?= number_format($order->grand_total ?? 0, 0, ',', '.'); ?></td>
                        <td class="px-4 py-3">
                            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $order->payment_method ?? 'N/A'))); ?>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="<?= BASEURL; ?>/admin/orders/updateStatus/<?= $order->id; ?>" class="inline">
                                <?php
                                    $status_classes = [
                                        'Paid' => 'bg-green-500/20 text-green-500',
                                        'Pending' => 'bg-yellow-500/20 text-yellow-500',
                                        'Processing' => 'bg-blue-500/20 text-blue-500',
                                        'Shipped' => 'bg-purple-500/20 text-purple-500',
                                        'Delivered' => 'bg-teal-500/20 text-teal-500',
                                        'Cancelled' => 'bg-red-500/20 text-red-500'
                                    ];

                                ?>
                                <select name="status" onchange="this.form.submit()" class="bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs p-1">
                                    <?php
                                        $statuses = ['Paid', 'Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
                                        foreach($statuses as $status):
                                            $selected = ($order->status === $status) ? 'selected' : '';
                                            $classes = $status_classes[$status] ?? 'bg-gray-500/20 text-gray-500';
                                            // Translate status to Indonesian
                                            $status_indo = [
                                                'Paid' => 'Lunas',
                                                'Pending' => 'Tertunda',
                                                'Processing' => 'Diproses',
                                                'Shipped' => 'Dikirim',
                                                'Delivered' => 'Diterima',
                                                'Cancelled' => 'Dibatalkan'
                                            ];
                                            $display_status = $status_indo[$status] ?? $status;
                                    ?>
                                        <option value="<?= $status; ?>" class="<?= $classes; ?>" <?= $selected; ?>><?= $display_status; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <a href="<?= BASEURL; ?>/admin/orders/show/<?= $order->id; ?>" class="text-blue-500 hover:text-blue-400">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if(empty($orders)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Tidak ada pesanan ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
// Konfirmasi untuk perubahan status
document.querySelectorAll('select[name="status"]').forEach(select => {
    select.addEventListener('change', function() {
        if(!confirm('Apakah Anda yakin ingin mengubah status pesanan?')) {
            // Reset ke nilai sebelumnya
            this.selectedIndex = Array.from(this.options).findIndex(option => option.defaultSelected);
        }
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>