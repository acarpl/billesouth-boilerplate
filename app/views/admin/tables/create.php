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
                <h1 class="text-3xl font-bold text-white">Add New Table</h1>
                <p class="text-gray-400">Enter table details below</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/tables/store" method="POST">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="table_number" class="block text-sm font-medium text-gray-300 mb-2">Table Number</label>
                            <input type="text" name="table_number" id="table_number" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter table number" required>
                        </div>

                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-300 mb-2">Branch</label>
                            <select name="branch_id" id="branch_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                <option value="">Select a branch</option>
                                <?php foreach($branches as $branch): ?>
                                    <option value="<?= $branch->id ?>"><?= htmlspecialchars($branch->branch_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="price_per_hour" class="block text-sm font-medium text-gray-300 mb-2">Price Per Hour (Rp)</label>
                            <input type="number" name="price_per_hour" id="price_per_hour" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter price per hour" required min="0">
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Table Type</label>
                            <select name="type" id="type" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                <option value="Regular">Regular</option>
                                <option value="VIP">VIP</option>
                                <option value="VVIP">VVIP</option>
                            </select>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                            <select name="status" id="status" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                <option value="Available">Available</option>
                                <option value="Pending">Pending</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Save Table
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>