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

    <!-- Bootstrap CSS for Modal -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-white">
    <div class="flex">
        <!-- Sidebar -->
        <?php $this->view('templates/admin_sidebar'); ?>

        <!-- Main Content -->
        <main class="flex-1 p-8 bg-[#030712] min-h-screen text-white ml-64">
            <!-- Page Title -->
            <header class="mb-10">
                <h1 class="text-3xl font-extrabold tracking-wide uppercase">Monitoring Billing</h1>
                <p class="text-gray-400 text-xs mt-1 tracking-widest uppercase">
                    Pantau semua sesi meja yang sedang berjalan
                </p>
            </header>

            <!-- Branch Filter -->
            <?php if ($_SESSION['user_role'] === 'super_admin' && !empty($data['branches'])): ?>
                <div class="bg-[#0A0F1C] rounded-xl p-6 mb-10 shadow-lg">
                    <label class="block text-[10px] uppercase tracking-widest text-gray-500 mb-2">
                        Filter Berdasarkan Cabang
                    </label>
                    <div class="flex flex-wrap items-center gap-3">
                        <select id="branch_filter"
                            class="bg-[#111827] rounded-lg px-4 py-2 text-sm text-gray-300 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="all">Semua Cabang</option>
                            <?php foreach ($data['branches'] as $branch): ?>
                                <option value="<?= $branch->id ?>"
                                    <?= (isset($_GET['branch_id']) && $_GET['branch_id'] == $branch->id) ? 'selected' : '' ?>>

                                    <?= htmlspecialchars($branch->branch_name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ACTIVE BILLING -->
            <section class="bg-[#0A0F1C] rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold uppercase tracking-widest">Sesi Aktif</h2>
                    <span class="text-xs text-gray-400 tracking-wide">
                        Total: <?= count($data['active_billings'] ?? []) ?>
                    </span>
                </div>

                <?php if (!empty($data['active_billings'])): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-800">
                                    <?php foreach (['Billing', 'Cabang', 'Meja', 'Mulai', 'Durasi', 'Pengguna', 'Status'] as $th): ?>
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-gray-500"><?= $th ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                <?php foreach ($data['active_billings'] as $billing): ?>
                                    <tr class="hover:bg-[#111827] transition">
                                        <td class="px-4 py-3"><?= $billing->billing_number ?></td>
                                        <td class="px-4 py-3"><?= $this->model('Branch_model')->getBranchById($billing->branch_id)->branch_name ?></td>
                                        <td class="px-4 py-3">
                                            <?php $t = $this->model('Table_model')->getTableById($billing->table_id); ?>
                                            <?= $t->table_number ?> (<?= strtoupper($t->type) ?>)
                                        </td>
                                        <td class="px-4 py-3"><?= date('d M Y H:i', strtotime($billing->start_time)) ?></td>
                                        <td class="px-4 py-3" id="duration-<?= $billing->id ?>">00:00:00</td>
                                        <td class="px-4 py-3">
                                            <?php
                                            if ($billing->user_id) {
                                                $user_model = $this->model('User_model');
                                                $user = $user_model->getUserById($billing->user_id);
                                                echo $user ? $user->name : 'N/A';
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest
                                        <?= $billing->status === 'Active' ? 'bg-emerald-600 text-emerald-50' : 'bg-gray-600 text-gray-200' ?>">
                                                <?= $billing->status ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-sm italic">Tidak ada sesi aktif.</p>
                <?php endif; ?>
            </section>

            <!-- FINISHED BILLING -->
            <section class="bg-[#0A0F1C] rounded-xl p-6 shadow-lg mt-10">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold uppercase tracking-widest">Sesi Selesai</h2>
                    <span class="text-xs text-gray-400 tracking-wide">
                        Total: <?= count($data['all_billings'] ?? []) ?>
                    </span>
                </div>
                <?php if (!empty($data['all_billings'])): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-800">
                                    <?php foreach (['Billing', 'Cabang', 'Meja', 'Mulai', 'selesai', 'Tipe Durasi', 'Status Pembayaran', 'Status', 'Aksi'] as $th): ?>
                                        <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-gray-500"><?= $th ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                <?php foreach ($data['all_billings'] as $billing): ?>
                                    <tr class="hover:bg-[#111827] transition">
                                        <td class="px-4 py-3"><?= $billing->billing_number ?></td>
                                        <td class="px-4 py-3"><?= $this->model('Branch_model')->getBranchById($billing->branch_id)->branch_name ?></td>
                                        <td class="px-4 py-3">
                                            <?php $t = $this->model('Table_model')->getTableById($billing->table_id); ?>
                                            <?= $t->table_number ?> (<?= strtoupper($t->type) ?>)
                                        </td>
                                        <td class="px-4 py-3"><?= date('d M Y H:i', strtotime($billing->start_time)) ?></td>
                                        <td class="px-4 py-3"><?= $billing->end_time ? date('d M Y H:i', strtotime($billing->end_time)) : '-' ?></td>
                                        <td class="px-4 py-3"><?= $billing->duration_type ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest
                                            <?= $billing->payment_status === 'Paid' ? 'bg-blue-500 text-blue-50' : 'bg-orange-500 text-orange-50' ?>">
                                                <?= $billing->payment_status ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest
                                            <?php
                                            if ($billing->status === 'Active'): echo 'bg-green-500 text-green-50';
                                            elseif ($billing->status === 'Finished'): echo 'bg-blue-500 text-blue-50';
                                            elseif ($billing->status === 'Canceled'): echo 'bg-red-500 text-red-50';
                                            endif;
                                            ?>">
                                                <?= $billing->status ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="window.location.href='<?= BASEURL ?>/admin/billing/detail/<?= $billing->id ?>'">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-sm italic">Tidak ada sesi aktif.</p>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>

    <script>
        // Function to calculate duration in real-time
        function updateBillingTimers() {
            const now = new Date();

            <?php foreach ($data['active_billings'] as $billing): ?>
                const startTime<?= $billing->id ?> = new Date('<?= $billing->start_time ?>');
                const diffMs<?= $billing->id ?> = now - startTime<?= $billing->id ?>;

                if (diffMs<?= $billing->id ?> >= 0) {
                    const diffSecs<?= $billing->id ?> = Math.floor(diffMs<?= $billing->id ?> / 1000);
                    const hours<?= $billing->id ?> = Math.floor(diffSecs<?= $billing->id ?> / 3600);
                    const minutes<?= $billing->id ?> = Math.floor((diffSecs<?= $billing->id ?> % 3600) / 60);
                    const seconds<?= $billing->id ?> = diffSecs<?= $billing->id ?> % 60;

                    const hDisplay<?= $billing->id ?> = hours<?= $billing->id ?> < 10 ? "0" + hours<?= $billing->id ?> : hours<?= $billing->id ?>;
                    const mDisplay<?= $billing->id ?> = minutes<?= $billing->id ?> < 10 ? "0" + minutes<?= $billing->id ?> : minutes<?= $billing->id ?>;
                    const sDisplay<?= $billing->id ?> = seconds<?= $billing->id ?> < 10 ? "0" + seconds<?= $billing->id ?> : seconds<?= $billing->id ?>;

                    const durationElement<?= $billing->id ?> = document.getElementById('duration-<?= $billing->id ?>');
                    if (durationElement<?= $billing->id ?>) {
                        durationElement<?= $billing->id ?>.innerText = hDisplay<?= $billing->id ?> + ":" + mDisplay<?= $billing->id ?> + ":" + sDisplay<?= $billing->id ?>;
                    }
                }
            <?php endforeach; ?>
        }

        // Update every second
        setInterval(updateBillingTimers, 1000);
        // Initial update
        updateBillingTimers();

        // Branch filter functionality - auto-refresh when selection changes
        document.getElementById('branch_filter').addEventListener('change', function() {
            const selectedBranchId = this.value;

            let url = '<?= BASEURL ?>/admin/billing';
            if (selectedBranchId !== 'all') {
                url += '?branch_id=' + selectedBranchId;
            }

            window.location.href = url;
        });
    </script>
</body>

</html>