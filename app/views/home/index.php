<?php require_once '../app/views/templates/header.php'; ?>

<!-- 1. HERO SECTION -->
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-black/60 z-10"></div>
        <!-- Video Background (Pastikan file ada di assets/video/ atau gunakan link placeholder) -->
        <video autoplay loop muted playsinline class="w-full h-full object-cover">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-professional-billiard-player-taking-a-shot-34537-large.mp4" type="video/mp4">
        </video>
    </div>

    <div class="relative z-20 text-center px-4">
        <p class="text-gray-300 tracking-[0.6em] text-xs md:text-sm mb-6 uppercase">House Of Champhion</p>
        <h1 class="text-6xl md:text-9xl font-bold tracking-tighter mb-8 italic uppercase text-white">
            BILLE <span class="text-transparent" style="-webkit-text-stroke: 1px white;">BILLIARDS</span>
        </h1>
        <div class="flex flex-col md:flex-row gap-6 justify-center items-center">
            <a href="<?= BASEURL; ?>/booking" class="group relative px-10 py-4 bg-white text-black font-bold tracking-widest overflow-hidden transition-all duration-300">
                <span class="relative z-10">RESERVASI MEJA</span>
                <div class="absolute inset-0 bg-gray-200 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            </a>
            <a href="<?= BASEURL; ?>/shop" class="px-10 py-4 border border-white text-white font-bold tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                LIHAT KOLEKSI
            </a>
        </div>
    </div>

    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
        <span class="text-[10px] tracking-widest mb-2 text-gray-500 uppercase">Scroll</span>
        <div class="w-[1px] h-12 bg-gradient-to-b from-white to-transparent"></div>
    </div>
</section>

<!-- 2. SPOTLIGHT BRANCH: CITRA RAYA TANGERANG -->
<section class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16">
            <div>
                <h2 class="text-gray-500 tracking-widest text-sm mb-2 uppercase">Lokasi Utama</h2>
                <h3 class="text-4xl font-bold uppercase text-white">Bille Citra Raya</h3>
            </div>
            <p class="text-gray-400 max-w-md text-sm mt-4 md:mt-0 italic">
                Destinasi billiard premium terbaru di jantung Citra Raya, Tangerang. Menawarkan atmosfer eksklusif dan meja standar turnamen.
            </p>
        </div>

        <!-- Single Featured Branch Card -->
        <div class="group relative bg-gray-900 overflow-hidden h-[550px] border border-gray-800">
            <!-- Ganti dengan foto asli cabang Citra Raya nanti -->
            <img src="https://images.unsplash.com/photo-1544111366-02e0878196b2?q=80&w=2070&auto=format&fit=crop" 
                 class="w-full h-full object-cover opacity-50 group-hover:scale-105 transition-transform duration-1000">
            
            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 p-10 w-full md:w-2/3">
                <div class="flex items-center gap-4 mb-4">
                    <span class="px-3 py-1 bg-white text-black text-[10px] font-bold uppercase">Open Now</span>
                    <span class="text-gray-400 text-xs tracking-widest uppercase">Citra Raya, Tangerang</span>
                </div>
                <h4 class="text-4xl md:text-5xl font-bold mb-4 uppercase text-white">Bille Citra Raya</h4>
                <p class="text-gray-400 text-sm mb-8 leading-relaxed max-w-xl">
                    Terletak di kawasan strategis Citra Raya, kami menghadirkan pengalaman bermain billiard yang tak terlupakan dengan layanan bintang lima dan fasilitas modern.
                </p>
                <div class="flex flex-wrap gap-6">
                    <a href="<?= BASEURL; ?>/booking" class="bg-white text-black px-8 py-3 font-bold text-xs tracking-widest hover:bg-gray-200 transition">
                        RESERVASI DI SINI
                    </a>
                    <div class="flex items-center text-gray-400 text-xs">
                        <i class="fa-solid fa-location-dot mr-2"></i>
                        Ruko Little Kyoto, Citra Raya Tangerang
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 3. SHOP TEASER -->
<section class="py-24 bg-gray-950">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative order-2 lg:order-1">
                <div class="absolute -top-10 -left-10 w-40 h-40 border-l border-t border-gray-800"></div>
                <!-- Foto Merchandise Premium -->
                <img src="https://images.unsplash.com/photo-1611095777215-8373f62ee8d0?q=80&w=2070&auto=format&fit=crop" 
                     class="relative z-10 w-full rounded-sm grayscale hover:grayscale-0 transition-all duration-700 shadow-2xl">
            </div>
            <div class="order-1 lg:order-2">
                <h2 class="text-gray-500 tracking-widest text-sm mb-2 uppercase">Koleksi Eksklusif</h2>
                <h3 class="text-5xl font-bold mb-6 uppercase text-white leading-tight">Gaya Lokal <br>Kualitas Global</h3>
                <p class="text-gray-400 mb-10 leading-relaxed text-sm">
                    Dari kaos berkualitas tinggi hingga perlengkapan billiard profesional. Koleksi Bille dirancang untuk mereka yang menghargai estetika dan performa.
                </p>
                <a href="<?= BASEURL; ?>/shop" class="inline-flex items-center gap-4 text-white font-bold tracking-widest group uppercase text-xs">
                    KUNJUNGI TOKO KAMI
                    <span class="w-12 h-[1px] bg-white group-hover:w-20 transition-all duration-300"></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 4. BILLIARD LAB (BLOG TEASER STATIS) -->
<section class="py-24 bg-black">
    <div class="max-w-7xl mx-auto px-4 text-center mb-16">
        <h2 class="text-gray-500 tracking-widest text-sm mb-2 uppercase">Edukasi</h2>
        <h3 class="text-4xl font-bold uppercase text-white">Billiard Lab</h3>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-12">
        <!-- Card 1 -->
        <article class="group cursor-pointer">
            <div class="mb-6 overflow-hidden bg-gray-900 aspect-video border border-gray-800">
                <img src="https://images.unsplash.com/photo-1542391039-44677761159b?q=80&w=2070&auto=format&fit=crop" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-60">
            </div>
            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Teknik</span>
            <h4 class="text-lg font-bold mt-2 text-white group-hover:text-gray-300 transition-colors italic">Rahasia Konsistensi Pukulan Draw Shot</h4>
        </article>

        <!-- Card 2 -->
        <article class="group cursor-pointer">
            <div class="mb-6 overflow-hidden bg-gray-900 aspect-video border border-gray-800">
                <img src="https://images.unsplash.com/photo-1609144414510-47b2c01990c0?q=80&w=2070&auto=format&fit=crop" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-60">
            </div>
            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Review</span>
            <h4 class="text-lg font-bold mt-2 text-white group-hover:text-gray-300 transition-colors italic">Review: Carbon Fiber vs Wood Cue</h4>
        </article>

        <!-- Card 3 -->
        <article class="group cursor-pointer">
            <div class="mb-6 overflow-hidden bg-gray-900 aspect-video border border-gray-800">
                <img src="https://images.unsplash.com/photo-1538391151322-9290076a0604?q=80&w=2070&auto=format&fit=crop" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-60">
            </div>
            <span class="text-[10px] text-gray-500 uppercase tracking-widest">Gaya Hidup</span>
            <h4 class="text-lg font-bold mt-2 text-white group-hover:text-gray-300 transition-colors italic">Etika Tak Tertulis di Meja Billiard</h4>
        </article>
    </div>
</section>

<?php require_once '../app/views/templates/footer.php'; ?>