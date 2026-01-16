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
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Active Billing</h1>
            <p class="text-gray-400">Monitor active tables and billing status</p>
        </div>

        <!-- Branch Filter -->
        <div class="mb-6">
            <label for="branch_filter" class="block text-sm font-medium text-gray-300 mb-2">Filter by Branch:</label>
            <?php if ($_SESSION['user_role'] === 'super_admin'): ?>
            <select id="branch_filter" class="bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="" <?= ($data['branch_id'] === null || $data['branch_id'] === '') ? 'selected' : '' ?>>All Branches</option>
                <?php foreach($data['branches'] as $branch): ?>
                    <option value="<?= $branch->id ?>" <?= ($data['branch_id'] == $branch->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($branch->branch_name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php else: ?>
            <select id="branch_filter" class="bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" disabled>
                <?php foreach($data['branches'] as $branch): ?>
                    <option value="<?= $branch->id ?>" selected>
                        <?= htmlspecialchars($branch->branch_name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="text-gray-400">Branch selection is only available for super admins</small>
            <?php endif; ?>
        </div>

        <!-- Active Tables Grid -->
        <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
            <h2 class="text-xl font-bold text-white mb-4">Table Status Overview</h2>
            
            <?php if(empty($data['tables'])): ?>
                <p class="text-gray-400">No active tables found.</p>
            <?php else: 
                // Separate tables by status
                $occupied_tables = [];
                $available_tables = [];
                
                foreach($data['tables'] as $table) {
                    if($table->table_status == 'Occupied') {
                        $occupied_tables[] = $table;
                    } else {
                        $available_tables[] = $table;
                    }
                }
            ?>
                <!-- Occupied Tables Section -->
                <?php if(!empty($occupied_tables)): ?>
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-red-400 mb-4 flex items-center">
                        <i class="fas fa-user-friends mr-2"></i> Occupied Tables (<?= count($occupied_tables); ?>)
                    </h3>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                        <?php foreach($occupied_tables as $table):
                            $table_type_lower = strtolower($table->type);

                            // Determine classes based on table type for occupied tables (only border, no fill)
                            if ($table_type_lower === 'vvip') {
                                $box_classes = "border-2 border-dashed border-purple-500"; // VVIP occupied: dashed purple border
                            } elseif ($table_type_lower === 'vip') {
                                $box_classes = "border-2 border-dashed border-yellow-400"; // VIP occupied: dashed yellow border
                            } else { // Regular table
                                $box_classes = "border-2 border-dashed border-green-500"; // Regular occupied: dashed green border
                            }
                        ?>
                        <div class="relative">
                            <div class="aspect-square rounded-lg flex flex-col items-center justify-center p-2 <?= $box_classes ?>">
                                <div class="font-bold text-sm">#<?= $table->table_number; ?></div>
                                <div class="text-xs mt-1 text-center"><?= ucfirst($table->type); ?></div>
                                <div class="text-xs mt-1 text-center text-red-400">
                                    <?= ucfirst($table->table_status); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Available Tables Section -->
                <?php if(!empty($available_tables)): ?>
                <div>
                    <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center">
                        <i class="fas fa-chair mr-2"></i> Available Tables (<?= count($available_tables); ?>)
                    </h3>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                        <?php foreach($available_tables as $table):
                            $table_type_lower = strtolower($table->type);

                            // Determine classes based on table type for available tables
                            if ($table_type_lower === 'vvip') {
                                $box_classes = "bg-purple-600 text-white"; // VVIP available: purple fill
                            } elseif ($table_type_lower === 'vip') {
                                $box_classes = "bg-yellow-500 text-gray-900"; // VIP available: yellow fill
                            } else { // Regular table
                                $box_classes = "bg-green-500 text-gray-900"; // Regular available: green fill
                            }
                        ?>
                        <div class="relative">
                            <div class="aspect-square rounded-lg flex flex-col items-center justify-center p-2 <?= $box_classes ?>">
                                <div class="font-bold text-sm">#<?= $table->table_number; ?></div>
                                <div class="text-xs mt-1 text-center"><?= ucfirst($table->type); ?></div>
                                <div class="text-xs mt-1 text-center text-green-200">
                                    <?= ucfirst($table->table_status); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(empty($occupied_tables) && empty($available_tables)): ?>
                    <p class="text-gray-400">No tables found.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
// Hanya jalankan script ini jika pengguna adalah super admin
<?php if ($_SESSION['user_role'] === 'super_admin'): ?>
document.getElementById('branch_filter').addEventListener('change', function() {
    const branchId = this.value;
    // Redirect ke halaman billing dengan parameter GET
    window.location.href = '<?= BASEURL; ?>/admin/billing?branch_id=' + branchId;
});
<?php endif; ?>
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>