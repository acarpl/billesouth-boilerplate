<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-black p-4">
    <div class="w-full max-w-md bg-gray-900 rounded-2xl shadow-xl overflow-hidden border border-gray-800">
        <div class="p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white tracking-[0.2em]">BILLE</h1>
                <p class="text-gray-400 mt-2">ADMIN LOGIN</p>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['flash_message'])): ?>
                <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['flash_type'] === 'success' ? 'bg-green-500/20 text-green-500' : 'bg-red-500/20 text-red-500'; ?> border <?php echo $_SESSION['flash_type'] === 'success' ? 'border-green-500' : 'border-red-500'; ?>">
                    <?php echo $_SESSION['flash_message']; ?>
                    <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASEURL; ?>/auth/login" method="POST">
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Enter your email" required>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <input type="password" name="password" id="password" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-3" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300">
                    SIGN IN
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500">
                    Don't have an account? 
                    <a href="<?= BASEURL; ?>/auth/register" class="text-blue-500 hover:text-blue-400 font-medium">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
</div>