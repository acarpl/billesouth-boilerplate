<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates\admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Edit Table</h1>
                <p class="text-gray-400">Update table details below</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/tables/update/<?= $table->id; ?>" method="POST">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="table_number" class="block text-sm font-medium text-gray-300 mb-2">Table Number</label>
                            <input type="number" name="table_number" id="table_number" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= htmlspecialchars($table->table_number); ?>" required>
                        </div>

                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-300 mb-2">Branch</label>
                            <select name="branch_id" id="branch_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                <option value="">Select a branch</option>
                                <?php foreach($branches as $branch): ?>
                                    <option value="<?= $branch->id ?>" <?= $table->branch_id == $branch->id ? 'selected' : '' ?>><?= htmlspecialchars($branch->branch_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-300 mb-2">Capacity</label>
                            <input type="number" name="capacity" id="capacity" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= htmlspecialchars($table->capacity); ?>" required>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Table Type</label>
                            <select name="type" id="type" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                <option value="regular" <?= $table->type == 'regular' ? 'selected' : '' ?>>Regular</option>
                                <option value="premium" <?= $table->type == 'premium' ? 'selected' : '' ?>>Premium</option>
                                <option value="vip" <?= $table->type == 'vip' ? 'selected' : '' ?>>VIP</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <select name="status" id="status" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                <option value="available" <?= $table->status == 'available' ? 'selected' : '' ?>>Available</option>
                                <option value="occupied" <?= $table->status == 'occupied' ? 'selected' : '' ?>>Occupied</option>
                                <option value="maintenance" <?= $table->status == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Update Table
                        </button>
                        <a href="<?= BASEURL; ?>/admin/tables" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>