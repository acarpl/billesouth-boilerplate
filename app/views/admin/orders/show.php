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
            <h1 class="text-3xl font-bold text-white">Order Details</h1>
            <p class="text-gray-400">Order #<?= $order->id; ?> - <?= date('M d, Y H:i', strtotime($order->created_at)); ?></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Information -->
            <div class="lg:col-span-2">
                <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mb-6">
                    <h2 class="text-xl font-bold text-white mb-4">Order Items</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                                <tr>
                                    <th class="px-4 py-3">Product</th>
                                    <th class="px-4 py-3">Price</th>
                                    <th class="px-4 py-3">Qty</th>
                                    <th class="px-4 py-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($order_items as $item): ?>
                                <tr class="border-b border-gray-800 hover:bg-gray-850">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center">
                                            <?php if($item->product_image): ?>
                                                <img src="<?= BASEURL . '/../' . $item->product_image; ?>" alt="<?= htmlspecialchars($item->product_name); ?>" class="w-10 h-10 object-cover rounded mr-3">
                                            <?php else: ?>
                                                <div class="w-10 h-10 bg-gray-700 rounded mr-3 flex items-center justify-center">
                                                    <i class="fas fa-image text-gray-500"></i>
                                                </div>
                                            <?php endif; ?>
                                            <span class="font-medium text-white"><?= htmlspecialchars($item->product_name); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">Rp <?= number_format($item->price, 0, ',', '.'); ?></td>
                                    <td class="px-4 py-3"><?= $item->quantity; ?></td>
                                    <td class="px-4 py-3">Rp <?= number_format($item->subtotal, 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Order Notes -->
                <?php if($order->notes): ?>
                <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                    <h2 class="text-xl font-bold text-white mb-4">Order Notes</h2>
                    <p class="text-gray-300"><?= htmlspecialchars($order->notes); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                    <h2 class="text-xl font-bold text-white mb-4">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Order ID:</span>
                            <span class="text-white">#<?= $order->id; ?></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Date:</span>
                            <span class="text-white"><?= date('M d, Y H:i', strtotime($order->created_at)); ?></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Payment Method:</span>
                            <span class="text-white"><?= ucfirst($order->payment_method); ?></span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Payment Status:</span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php 
                                    if($order->payment_status == 'paid'): echo 'bg-green-500/20 text-green-500';
                                    elseif($order->payment_status == 'pending'): echo 'bg-yellow-500/20 text-yellow-500';
                                    else: echo 'bg-red-500/20 text-red-500';
                                    endif; 
                                ?>">
                                <?= ucfirst($order->payment_status); ?>
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-400">Order Status:</span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php 
                                    if($order->status == 'pending'): echo 'bg-yellow-500/20 text-yellow-500';
                                    elseif($order->status == 'processing'): echo 'bg-blue-500/20 text-blue-500';
                                    elseif($order->status == 'shipped'): echo 'bg-indigo-500/20 text-indigo-500';
                                    elseif($order->status == 'delivered'): echo 'bg-green-500/20 text-green-500';
                                    else: echo 'bg-red-500/20 text-red-500';
                                    endif; 
                                ?>">
                                <?= ucfirst($order->status); ?>
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-gray-800 pt-4">
                        <div class="flex justify-between font-bold text-lg text-white">
                            <span>Total:</span>
                            <span>Rp <?= number_format($order->total_amount, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mt-6">
                    <h2 class="text-xl font-bold text-white mb-4">Customer Information</h2>
                    
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-400 text-sm">Name:</span>
                            <p class="text-white"><?= htmlspecialchars($order->customer_name ?? 'Guest'); ?></p>
                        </div>
                        
                        <?php if($order->customer_email): ?>
                        <div>
                            <span class="text-gray-400 text-sm">Email:</span>
                            <p class="text-white"><?= htmlspecialchars($order->customer_email); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($order->customer_phone): ?>
                        <div>
                            <span class="text-gray-400 text-sm">Phone:</span>
                            <p class="text-white"><?= htmlspecialchars($order->customer_phone); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($order->shipping_address): ?>
                        <div>
                            <span class="text-gray-400 text-sm">Shipping Address:</span>
                            <p class="text-white"><?= htmlspecialchars($order->shipping_address); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Update Status Form -->
                <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 mt-6">
                    <h2 class="text-xl font-bold text-white mb-4">Update Order Status</h2>
                    
                    <form method="POST" action="<?= BASEURL; ?>/admin/orders/updateStatus/<?= $order->id; ?>">
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-300 mb-2">New Status</label>
                            <select name="status" id="status" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                <option value="pending" <?= $order->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?= $order->status == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?= $order->status == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?= $order->status == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?= $order->status == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>