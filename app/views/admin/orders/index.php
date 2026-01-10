<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Order Management</h1>
            <p class="text-gray-400">Manage merchandise orders and their status</p>
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
                        <th class="px-4 py-3">Order ID</th>
                        <th class="px-4 py-3">Customer</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                    <tr class="border-b border-gray-800 hover:bg-gray-850">
                        <td class="px-4 py-3 font-medium text-white">#<?= $order->id; ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($order->customer_name ?? 'Guest'); ?></td>
                        <td class="px-4 py-3"><?= date('M d, Y H:i', strtotime($order->created_at)); ?></td>
                        <td class="px-4 py-3">Rp <?= number_format($order->total_amount, 0, ',', '.'); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php 
                                    if($order->payment_status == 'paid'): echo 'bg-green-500/20 text-green-500';
                                    elseif($order->payment_status == 'pending'): echo 'bg-yellow-500/20 text-yellow-500';
                                    else: echo 'bg-red-500/20 text-red-500';
                                    endif; 
                                ?>">
                                <?= ucfirst($order->payment_status); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form method="POST" action="<?= BASEURL; ?>/admin/orders/updateStatus/<?= $order->id; ?>" class="inline">
                                <select name="status" onchange="this.form.submit()" class="bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 text-xs p-1">
                                    <option value="pending" <?= $order->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?= $order->status == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="shipped" <?= $order->status == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?= $order->status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?= $order->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
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
                    <p>No orders found.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
// Confirmation for status change
document.querySelectorAll('select[name="status"]').forEach(select => {
    select.addEventListener('change', function() {
        if(!confirm('Are you sure you want to change the order status?')) {
            // Reset to previous value
            this.selectedIndex = Array.from(this.options).findIndex(option => option.defaultSelected);
        }
    });
});
</script>