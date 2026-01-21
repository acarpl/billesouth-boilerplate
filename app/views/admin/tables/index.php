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
    <main class="flex-1 p-8 bg-gray-950 min-h-screen ml-64">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Manajemen Meja</h1>
            <p class="text-gray-400">Kelola meja tempat biliar Anda</p>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <?php if ($_SESSION['user_role'] === 'super_admin'): ?>
                    <div class="relative inline-block group">
                        <select id="branchFilter" class="bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pr-8 appearance-none">
                            <option value="">Semua Cabang</option>
                            <?php foreach($branches as $branch): ?>
                                <option value="<?= $branch->id; ?>" <?= ($selected_branch_id == $branch->id) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($branch->branch_name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                <?php else: ?>
                    <span class="text-gray-400 text-sm">Menampilkan meja untuk cabang Anda</span>
                <?php endif; ?>
            </div>
            <a href="<?= BASEURL; ?>/admin/tables/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Meja Baru
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                <?php echo $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>

        <!-- Tables Table -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                    <tr>
                        <th class="px-4 py-3">No. Meja</th>
                        <th class="px-4 py-3">Cabang</th>
                        <th class="px-4 py-3">Harga/jam</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tables as $table): ?>
                    <tr class="border-b border-gray-800 hover:bg-gray-850">
                        <td class="px-4 py-3 font-medium text-white">#<?= htmlspecialchars($table->table_number); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($table->branch_name ?? 'Unknown Branch'); ?></td>
                        <td class="px-4 py-3">Rp <?= number_format($table->price_per_hour ?? 0, 0, ',', '.'); ?>/hour</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full
                                <?php
                                    if($table->type == 'VVIP'): echo 'bg-purple-500/20 text-purple-500';
                                    elseif($table->type == 'VIP'): echo 'bg-yellow-500/20 text-yellow-500';
                                    else: echo 'bg-gray-500/20 text-gray-500';
                                    endif;
                                ?>">
                                <?= htmlspecialchars($table->type); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full
                                <?php
                                    if($table->status == 'Available'): echo 'bg-green-500/20 text-green-500';
                                    elseif($table->status == 'Occupied'): echo 'bg-red-500/20 text-red-500';
                                    elseif($table->status == 'Pending'): echo 'bg-yellow-500/20 text-yellow-500';
                                    else: echo 'bg-gray-500/20 text-gray-500';
                                    endif;
                                ?>">
                                <?= htmlspecialchars($table->status); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="<?= BASEURL; ?>/admin/tables/edit/<?= $table->id; ?>" class="text-blue-500 hover:text-blue-400">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" onclick="confirmDelete(<?= $table->id; ?>)" class="text-red-500 hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if(empty($tables)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>Tidak ada meja ditemukan. <a href="<?= BASEURL; ?>/admin/tables/create" class="text-blue-500 hover:underline">Tambahkan meja pertama Anda</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function confirmDelete(id) {
    if(confirm('Are you sure you want to delete this table?')) {
        // Create a form and submit it to delete the table
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASEURL; ?>/admin/tables/destroy/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}

// Handle branch filter change
document.addEventListener('DOMContentLoaded', function() {
    const branchFilter = document.getElementById('branchFilter');
    if (branchFilter) {
        branchFilter.addEventListener('change', function() {
            const selectedValue = this.value;
            let url = new URL(window.location);
            if (selectedValue) {
                url.searchParams.set('branch_id', selectedValue);
            } else {
                url.searchParams.delete('branch_id');
            }
            window.location.href = url.toString();
        });
    }
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>