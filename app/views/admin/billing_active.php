<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Active Billing</h1>
            <p class="text-gray-400">Monitor active tables and billing status</p>
        </div>

        <!-- Branch Filter -->
        <div class="mb-6">
            <label for="branch_filter" class="block text-sm font-medium text-gray-300 mb-2">Filter by Branch:</label>
            <select id="branch_filter" class="bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">All Branches</option>
                <?php foreach($branches as $branch): ?>
                    <option value="<?= $branch->id ?>" <?= ($branch_id == $branch->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($branch->branch_name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Active Tables Grid -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-4">Table Status Overview</h2>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                <?php foreach($tables as $table): ?>
                <div class="relative">
                    <div class="aspect-square bg-gray-800 rounded-lg border-2 flex flex-col items-center justify-center p-2
                        <?php 
                            if($table->table_status == 'available'): echo 'border-green-500';
                            elseif($table->table_status == 'occupied'): echo 'border-red-500';
                            else: echo 'border-yellow-500';
                            endif; 
                        ?>">
                        <div class="font-bold text-white text-sm">#<?= $table->table_number; ?></div>
                        <div class="text-xs text-gray-400 mt-1 text-center"><?= $table->branch_name; ?></div>
                        <div class="text-xs mt-1 text-center
                            <?php 
                                if($table->table_status == 'available'): echo 'text-green-500';
                                elseif($table->table_status == 'occupied'): echo 'text-red-500';
                                else: echo 'text-yellow-500';
                                endif; 
                            ?>">
                            <?= ucfirst($table->table_status); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
</div>

<script>
document.getElementById('branch_filter').addEventListener('change', function() {
    const branchId = this.value;
    window.location.href = '<?= BASEURL; ?>/admin/billing_active' + (branchId ? '/' + branchId : '');
});
</script>