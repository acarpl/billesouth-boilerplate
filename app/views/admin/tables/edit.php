<?php
$table = $data['table'];
$branches = $data['branches'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">

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
                <h1 class="text-3xl font-bold">Edit Meja</h1>
                <p class="text-gray-400">Perbarui detail meja</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">

                <form action="<?= BASEURL; ?>/admin/tables/update/<?= $table->id; ?>" method="POST">

                    <div class="grid gap-6">

                        <!-- Nomor Meja -->
                        <div>
                            <label class="block text-sm mb-2">Nomor Meja</label>
                            <input
                                type="text"
                                name="table_number"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5"
                                value="<?= htmlspecialchars($table->table_number); ?>"
                                required
                            >
                        </div>

                        <!-- Cabang -->
                        <div>
                            <label class="block text-sm mb-2">Cabang</label>

                            <select name="branch_id"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5"
                                required>

                                <option value="">Pilih Cabang</option>

                                <?php foreach($branches as $branch): ?>
                                    <option
                                        value="<?= $branch->id ?>"
                                        <?= $table->branch_id == $branch->id ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($branch->branch_name) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-sm mb-2">Harga Per Jam (Rp)</label>

                            <input
                                type="number"
                                name="price_per_hour"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5"
                                value="<?= (int)$table->price_per_hour ?>"
                                required
                                min="0"
                            >
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm mb-2">Tipe Meja</label>

                            <select name="type"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5">

                                <?php
                                $types = ['Regular','VIP','VVIP'];
                                foreach ($types as $type):
                                ?>
                                    <option
                                        value="<?= $type ?>"
                                        <?= $table->type == $type ? 'selected' : '' ?>
                                    >
                                        <?= $type ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm mb-2">Status</label>

                            <select name="status"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg p-2.5">

                                <?php
                                $statuses = ['Available','Pending','Occupied','Maintenance'];
                                foreach ($statuses as $status):
                                ?>
                                    <option
                                        value="<?= $status ?>"
                                        <?= $table->status == $status ? 'selected' : '' ?>
                                    >
                                        <?= $status ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                    </div>

                    <!-- Button -->
                    <div class="mt-8 flex gap-4">
                        <button class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-bold">
                            Update
                        </button>

                        <a href="<?= BASEURL; ?>/admin/tables"
                           class="bg-gray-700 hover:bg-gray-600 px-6 py-2 rounded-lg font-bold">
                            Batal
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
