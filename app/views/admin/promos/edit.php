<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Edit Promo</h1>
                <p class="text-gray-400">Update promo details below</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/promos/update/<?= $promo->id; ?>" method="POST">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-300 mb-2">Promo Code</label>
                            <input type="text" name="code" id="code" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= htmlspecialchars($promo->code); ?>" required>
                            <p class="mt-1 text-sm text-gray-500">Will be converted to uppercase automatically</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"><?= htmlspecialchars($promo->description); ?></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="discount_type" class="block text-sm font-medium text-gray-300 mb-2">Discount Type</label>
                                <select name="discount_type" id="discount_type" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                    <option value="percentage" <?= $promo->discount_type == 'percentage' ? 'selected' : '' ?>>Percentage (%)</option>
                                    <option value="fixed" <?= $promo->discount_type == 'fixed' ? 'selected' : '' ?>>Fixed Amount (Rp)</option>
                                </select>
                            </div>

                            <div>
                                <label for="discount_value" class="block text-sm font-medium text-gray-300 mb-2">Discount Value</label>
                                <input type="number" name="discount_value" id="discount_value" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $promo->discount_value; ?>" required min="0">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="valid_from" class="block text-sm font-medium text-gray-300 mb-2">Valid From</label>
                                <input type="date" name="valid_from" id="valid_from" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $promo->valid_from; ?>" required>
                            </div>

                            <div>
                                <label for="valid_until" class="block text-sm font-medium text-gray-300 mb-2">Valid Until</label>
                                <input type="date" name="valid_until" id="valid_until" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $promo->valid_until; ?>" required>
                            </div>
                        </div>

                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-300 mb-2">Usage Limit (optional)</label>
                            <input type="number" name="usage_limit" id="usage_limit" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $promo->usage_limit ?: ''; ?>" placeholder="Leave blank for unlimited usage" min="0">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <select name="status" id="status" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                <option value="active" <?= $promo->status == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $promo->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Update Promo
                        </button>
                        <a href="<?= BASEURL; ?>/admin/promos" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>