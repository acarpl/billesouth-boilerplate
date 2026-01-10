<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
            <p class="text-gray-400">Manage your billiard parlor operations</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20 mr-4">
                        <i class="fas fa-calendar-check text-blue-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Bookings</p>
                        <p class="text-2xl font-bold text-white"><?= $total_bookings; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500/20 mr-4">
                        <i class="fas fa-box text-green-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Products</p>
                        <p class="text-2xl font-bold text-white"><?= $total_products; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500/20 mr-4">
                        <i class="fas fa-table text-yellow-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Tables</p>
                        <p class="text-2xl font-bold text-white"><?= $total_tables; ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500/20 mr-4">
                        <i class="fas fa-store text-purple-500 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Branches</p>
                        <p class="text-2xl font-bold text-white"><?= $total_branches; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Active Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Bookings -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h2 class="text-xl font-bold text-white mb-4">Recent Bookings</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-800">
                            <tr>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Time</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_bookings as $booking): ?>
                            <tr class="border-b border-gray-800 hover:bg-gray-850">
                                <td class="px-4 py-3 font-medium text-white"><?= htmlspecialchars($booking->customer_name); ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars(date('M d, Y', strtotime($booking->booking_date))); ?></td>
                                <td class="px-4 py-3"><?= htmlspecialchars($booking->start_time . ' - ' . $booking->end_time); ?></td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        <?php 
                                            if($booking->status == 'confirmed'): echo 'bg-green-500/20 text-green-500';
                                            elseif($booking->status == 'pending'): echo 'bg-yellow-500/20 text-yellow-500';
                                            else: echo 'bg-red-500/20 text-red-500';
                                            endif; 
                                        ?>">
                                        <?= ucfirst($booking->status); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active Tables Grid -->
            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <h2 class="text-xl font-bold text-white mb-4">Active Tables</h2>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-4">
                    <?php foreach($active_tables as $table): ?>
                    <div class="relative">
                        <div class="aspect-square bg-gray-800 rounded-lg border-2 flex items-center justify-center text-center p-2
                            <?php 
                                if($table->table_status == 'available'): echo 'border-green-500';
                                elseif($table->table_status == 'occupied'): echo 'border-red-500';
                                else: echo 'border-yellow-500';
                                endif; 
                            ?>">
                            <div>
                                <div class="font-bold text-white">#<?= $table->table_number; ?></div>
                                <div class="text-xs text-gray-400 mt-1"><?= $table->branch_name; ?></div>
                                <div class="text-xs mt-1
                                    <?php 
                                        if($table->table_status == 'available'): echo 'text-green-500';
                                        elseif($table->table_status == 'occupied'): echo 'text-red-500';
                                        else: echo 'text-yellow-500';
                                        endif; 
                                    ?>">
                                    <?= ucfirst($table->table_status); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>