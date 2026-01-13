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
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Add New Product</h1>
                <p class="text-gray-400">Enter product details below</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/products/store" method="POST" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Product Name</label>
                            <input type="text" name="name" id="name" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter product name" required>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                            <select name="category_id" id="category_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                <option value="">Select a category</option>
                                <option value="1">Merchandise</option>
                                <option value="2">F&B</option>
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter product description"></textarea>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-300 mb-2">Price (Rp)</label>
                            <input type="number" name="price" id="price" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter price" required>
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-300 mb-2">Active Status</label>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" class="w-4 h-4 text-blue-600 bg-gray-800 border-gray-700 rounded focus:ring-blue-500" checked>
                                <label for="is_active" class="ml-2 text-sm text-gray-300">Product is active</label>
                            </div>
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-300 mb-2">Product Image</label>
                            <input type="file" name="image" id="image" accept="image/*" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                            <p class="mt-1 text-sm text-gray-500">Upload a product image (optional)</p>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Save Product
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>