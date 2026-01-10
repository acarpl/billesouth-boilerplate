<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Add New Branch</h1>
                <p class="text-gray-400">Enter branch details below</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/branches/store" method="POST">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="branch_name" class="block text-sm font-medium text-gray-300 mb-2">Branch Name</label>
                            <input type="text" name="branch_name" id="branch_name" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter branch name" required>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                            <input type="text" name="location" id="location" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter branch location" required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                            <input type="tel" name="phone" id="phone" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter phone number" required>
                        </div>

                        <div>
                            <label for="opening_hours" class="block text-sm font-medium text-gray-300 mb-2">Opening Hours</label>
                            <textarea name="opening_hours" id="opening_hours" rows="3" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Enter opening hours (e.g., Mon-Sun: 10AM-12AM)"></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Save Branch
                        </button>
                        <a href="<?= BASEURL; ?>/admin/branches" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>