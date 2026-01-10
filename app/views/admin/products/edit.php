<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Edit Product</h1>
                <p class="text-gray-400">Update product details below</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/products/update/<?= $product->id; ?>" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="existing_image" value="<?= $product->image; ?>">
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Product Name</label>
                            <input type="text" name="name" id="name" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= htmlspecialchars($product->name); ?>" required>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5"><?= htmlspecialchars($product->description); ?></textarea>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-300 mb-2">Price (Rp)</label>
                            <input type="number" name="price" id="price" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $product->price; ?>" required>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                            <select name="category" id="category" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                <option value="food" <?= $product->category == 'food' ? 'selected' : '' ?>>Food</option>
                                <option value="drink" <?= $product->category == 'drink' ? 'selected' : '' ?>>Drink</option>
                                <option value="merchandise" <?= $product->category == 'merchandise' ? 'selected' : '' ?>>Merchandise</option>
                                <option value="equipment" <?= $product->category == 'equipment' ? 'selected' : '' ?>>Equipment</option>
                            </select>
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-300 mb-2">Stock Quantity</label>
                            <input type="number" name="stock" id="stock" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" value="<?= $product->stock; ?>">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <select name="status" id="status" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                <option value="active" <?= $product->status == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $product->status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-300 mb-2">Product Image</label>
                            <?php if($product->image): ?>
                                <div class="mb-2">
                                    <img src="<?= BASEURL . '/../' . $product->image; ?>" alt="<?= htmlspecialchars($product->name); ?>" class="w-24 h-24 object-cover rounded">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="image" id="image" accept="image/*" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <p class="mt-1 text-sm text-gray-500">Choose a new image to replace the current one (optional)</p>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Update Product
                        </button>
                        <a href="<?= BASEURL; ?>/admin/products" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>