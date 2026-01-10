<!-- Footer -->
<footer class="bg-gray-900 border-t border-gray-800 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <!-- Brand -->
            <div class="col-span-1 md:col-span-1">
                <h3 class="text-2xl font-bold tracking-widest text-white mb-4">BILLE</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Premium Billiard Parlor & Lifestyle Hub. Experience the art of precision and luxury.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-bold mb-4 text-sm">EXPLORE</h4>
                <ul class="text-gray-500 text-sm space-y-2">
                    <li><a href="<?= BASEURL; ?>/booking" class="hover:text-white">Booking Table</a></li>
                    <li><a href="<?= BASEURL; ?>/shop" class="hover:text-white">The Shop</a></li>
                    <li><a href="<?= BASEURL; ?>/events" class="hover:text-white">Billiard Lab (Blog)</a></li>
                </ul>
            </div>

            <!-- Branches -->
            <div>
                <h4 class="text-white font-bold mb-4 text-sm">LOCATIONS</h4>
                <ul class="text-gray-500 text-sm space-y-2">
                    <li>Kelapa Gading</li>
                    <li>BSD City</li>
                    <li>Coming Soon: Kemang</li>
                </ul>
            </div>

            <!-- Social Media -->
            <div>
                <h4 class="text-white font-bold mb-4 text-sm">CONNECT</h4>
                <div class="flex space-x-4 text-xl text-gray-400">
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#" class="hover:text-white"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-gray-600 text-xs">
            <p>&copy; <?= date('Y'); ?> Bille Billiards. All Rights Reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-gray-400">Privacy Policy</a>
                <a href="#" class="hover:text-gray-400">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>