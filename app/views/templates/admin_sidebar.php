<aside class="w-64 bg-gray-900 min-h-screen border-r border-gray-800 hidden md:block">
    <div class="p-6">
        <h3 class="text-white font-bold tracking-[0.2em] mb-10">BILLE ADMIN</h3>

        <nav class="space-y-4">
            <a href="<?= BASEURL; ?>/admin" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-chart-line w-8"></i> Dashboard
            </a>
            <a href="<?= BASEURL; ?>/admin/billing" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-stopwatch w-8"></i> Active Billing
            </a>
            <a href="<?= BASEURL; ?>/admin/bookings" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-calendar-check w-8"></i> Reservations
            </a>
            <a href="<?= BASEURL; ?>/admin/inventory" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-box w-8"></i> Shop / Inventory
            </a>

            <?php if ($_SESSION['user_role'] === 'super_admin'): ?>
            <div class="border-t border-gray-800 my-4"></div>
            <a href="<?= BASEURL; ?>/admin/branches" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-store w-8"></i> Branches
            </a>
            <a href="<?= BASEURL; ?>/admin/tables" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-table w-8"></i> Tables
            </a>
            <a href="<?= BASEURL; ?>/admin/products" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-boxes w-8"></i> Products
            </a>
            <a href="<?= BASEURL; ?>/admin/promos" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-percentage w-8"></i> Promos
            </a>
            <a href="<?= BASEURL; ?>/admin/orders" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-shopping-cart w-8"></i> Orders
            </a>
            <a href="<?= BASEURL; ?>/admin/reports" class="flex items-center text-gray-400 hover:text-white px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-chart-bar w-8"></i> Reports
            </a>
            <?php endif; ?>

            <a href="<?= BASEURL; ?>/auth/logout" class="flex items-center text-red-500 hover:text-red-400 px-2 py-2 text-sm font-medium">
                <i class="fa-solid fa-right-from-bracket w-8"></i> Logout
            </a>
        </nav>
    </div>
</aside>