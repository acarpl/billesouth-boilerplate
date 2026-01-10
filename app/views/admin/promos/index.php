<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Promo Management</h1>
            <p class="text-gray-400">Manage your billiard parlor promotions and discounts</p>
        </div>

        <div class="mb-6">
            <a href="<?= BASEURL; ?>/admin/promos/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Promo
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                <?php echo $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>

        <!-- Promos Table -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                    <tr>
                        <th class="px-4 py-3">Code</th>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">Discount</th>
                        <th class="px-4 py-3">Valid From</th>
                        <th class="px-4 py-3">Valid Until</th>
                        <th class="px-4 py-3">Usage</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($promos as $promo): ?>
                    <tr class="border-b border-gray-800 hover:bg-gray-850">
                        <td class="px-4 py-3 font-medium text-white">
                            <span class="bg-blue-500/20 text-blue-500 px-2 py-1 rounded"><?= htmlspecialchars($promo->code); ?></span>
                        </td>
                        <td class="px-4 py-3"><?= htmlspecialchars($promo->description); ?></td>
                        <td class="px-4 py-3">
                            <?php if($promo->discount_type == 'percentage'): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-500">
                                    <?= $promo->discount_value; ?>% OFF
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-500">
                                    Rp <?= number_format($promo->discount_value, 0, ',', '.'); ?> OFF
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3"><?= date('M d, Y', strtotime($promo->valid_from)); ?></td>
                        <td class="px-4 py-3"><?= date('M d, Y', strtotime($promo->valid_until)); ?></td>
                        <td class="px-4 py-3">
                            <?php if($promo->usage_limit): ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-500">
                                    <?= $promo->used_count; ?>/<?= $promo->usage_limit; ?>
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-500/20 text-gray-500">
                                    Unlimited
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php 
                                    if($promo->status == 'active' && strtotime($promo->valid_until) >= time()): echo 'bg-green-500/20 text-green-500';
                                    else: echo 'bg-red-500/20 text-red-500';
                                    endif; 
                                ?>">
                                <?= ucfirst($promo->status); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="<?= BASEURL; ?>/admin/promos/edit/<?= $promo->id; ?>" class="text-blue-500 hover:text-blue-400">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" onclick="confirmDelete(<?= $promo->id; ?>)" class="text-red-500 hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if(empty($promos)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>No promos found. <a href="<?= BASEURL; ?>/admin/promos/create" class="text-blue-500 hover:underline">Add your first promo</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function confirmDelete(id) {
    if(confirm('Are you sure you want to delete this promo?')) {
        // Create a form and submit it to delete the promo
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASEURL; ?>/admin/promos/destroy/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>