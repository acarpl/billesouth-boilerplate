<section class="min-h-screen bg-black pt-40 pb-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20">
            
            <!-- Foto Produk -->
            <div class="bg-gray-900 aspect-square overflow-hidden">
                <img src="<?= BASEURL ?>/uploads/products/<?= $data['product']->image ?>" class="w-full h-full object-cover">
            </div>

            <!-- Informasi Produk -->
            <div class="flex flex-col justify-center">
                <nav class="flex text-[10px] text-gray-500 gap-2 mb-6 uppercase tracking-widest">
                    <a href="<?= BASEURL ?>/shop" class="hover:text-white">Shop</a>
                    <span>/</span>
                    <span class="text-gray-300"><?= $data['product']->category_name ?></span>
                </nav>

                <h1 class="text-5xl font-bold text-white uppercase tracking-tighter mb-4"><?= $data['product']->name ?></h1>
                <p class="text-2xl text-gray-400 font-light mb-8">Rp <?= number_format($data['product']->price, 0, ',', '.') ?></p>
                
                <div class="border-t border-gray-800 pt-8 mb-10">
                    <h4 class="text-[10px] font-bold text-white uppercase tracking-[0.3em] mb-4">Deskripsi</h4>
                    <p class="text-gray-400 text-sm leading-relaxed italic">
                        <?= $data['product']->description ?>
                    </p>
                </div>

                <!-- Tombol Beli (Direct to WA) -->
                <?php 
                    $pesan = "Halo Bille! Saya ingin memesan " . $data['product']->name . ". Apakah stoknya tersedia?";
                    $linkWA = "https://wa.me/628123456789?text=" . urlencode($pesan);
                ?>
                <a href="<?= $linkWA ?>" target="_blank" class="w-full bg-white text-black text-center py-5 font-bold tracking-[0.3em] uppercase hover:bg-gray-200 transition">
                    Beli Sekarang via WhatsApp
                </a>

                <div class="mt-10 flex gap-8 text-[9px] text-gray-600 font-bold uppercase tracking-widest">
                    <div class="flex items-center gap-2"><i class="fa-solid fa-shield"></i> Jaminan Kualitas</div>
                    <div class="flex items-center gap-2"><i class="fa-solid fa-truck"></i> Pengiriman Seluruh Indonesia</div>
                </div>
            </div>

        </div>
    </div>
</section>