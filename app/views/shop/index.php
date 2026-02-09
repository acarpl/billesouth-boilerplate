<section class="min-h-screen bg-black pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header Toko -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 border-b border-gray-800 pb-8">
            <div>
                <h2 class="text-gray-500 tracking-[0.4em] text-xs uppercase mb-2">Peralatan & Gaya Hidup</h2>
                <h1 class="text-5xl font-bold uppercase tracking-tighter">THE SHOP</h1>
            </div>
            <!-- Filter Kategori Sederhana -->
            <div class="flex gap-6 text-[10px] font-bold tracking-widest uppercase mt-6 md:mt-0">
                <a href="#" class="text-white border-b-2 border-white pb-1">Semua</a>
                <?php foreach($data['categories'] as $cat) : ?>
                    <a href="#" class="text-gray-500 hover:text-white transition"><?= $cat->category_name ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Grid Produk -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <?php foreach($data['products'] as $product) : ?>
    <div class="group">
        <a href="<?= BASEURL ?>/shop/detail/<?= $product->id ?>">
            <div class="relative aspect-[4/5] bg-gray-900 overflow-hidden mb-6">
                <div class="absolute top-4 left-4 z-10">
                    <span class="bg-white text-black text-[9px] font-bold px-2 py-1 uppercase tracking-tighter">
                        <!-- Pakai null coalescing (??) biar kalau kategori kosong gak error -->
                        <?= $product->category_name ?? 'Bille Product'; ?>
                    </span>
                </div>
                
                <!-- PASTIKAN PATH FOLDERNYA BENAR -->
                <img src="<?= BASEURL ?>/<?= $product->image ?>" 
                     alt="<?= $product->name ?>"
                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700">
            </div>
            
            <div class="text-center">
                <h3 class="text-white font-bold text-sm uppercase tracking-tight mb-1"><?= $product->name ?></h3>
                <p class="text-gray-500 text-xs tracking-widest">Rp <?= number_format($product->price, 0, ',', '.') ?></p>
            </div>
        </a>
    </div>
<?php endforeach; ?>
        </div>

    </div>
</section>