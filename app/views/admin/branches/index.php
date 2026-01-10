<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Branch Management</h1>
            <p class="text-gray-400">Manage your billiard parlor branches</p>
        </div>

        <div class="mb-6">
            <a href="<?= BASEURL; ?>/admin/branches/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg inline-flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Branch
            </a>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                <?php echo $_SESSION['flash_message']; ?>
                <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
            </div>
        <?php endif; ?>

        <!-- Branches Table -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-400">
                <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                    <tr>
                        <th class="px-4 py-3">Branch Name</th>
                        <th class="px-4 py-3">Location</th>
                        <th class="px-4 py-3">Phone</th>
                        <th class="px-4 py-3">Opening Hours</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($branches as $branch): ?>
                    <tr class="border-b border-gray-800 hover:bg-gray-850">
                        <td class="px-4 py-3 font-medium text-white"><?= htmlspecialchars($branch->branch_name); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($branch->location); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($branch->phone); ?></td>
                        <td class="px-4 py-3"><?= htmlspecialchars($branch->opening_hours); ?></td>
                        <td class="px-4 py-3">
                            <div class="flex space-x-2">
                                <a href="<?= BASEURL; ?>/admin/branches/edit/<?= $branch->id; ?>" class="text-blue-500 hover:text-blue-400">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" onclick="confirmDelete(<?= $branch->id; ?>)" class="text-red-500 hover:text-red-400">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if(empty($branches)): ?>
                <div class="text-center py-8 text-gray-500">
                    <p>No branches found. <a href="<?= BASEURL; ?>/admin/branches/create" class="text-blue-500 hover:underline">Add your first branch</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function confirmDelete(id) {
    if(confirm('Are you sure you want to delete this branch?')) {
        // Create a form and submit it to delete the branch
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASEURL; ?>/admin/branches/destroy/' + id;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>