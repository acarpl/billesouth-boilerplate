<?php require_once '../app/views/templates/header.php'; ?>

<section class="min-h-screen bg-black pt-32 pb-20">
    <div class="max-w-4xl mx-auto px-4">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- KIRI: RINGKASAN PESANAN -->
            <div class="text-white">
                <div class="mb-8">
                    <h2 class="text-gray-500 tracking-widest text-xs uppercase mb-2">Langkah 2</h2>
                    <h1 class="text-3xl font-bold uppercase tracking-tighter">Ringkasan Reservasi</h1>
                </div>

                <div class="bg-gray-900 border border-gray-800 p-8 space-y-6">
                    <div class="flex justify-between border-b border-gray-800 pb-4">
                        <span class="text-gray-500 text-xs uppercase">Kode Booking</span>
                        <span class="font-mono font-bold text-white"><?= $data['booking']->booking_code ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-800 pb-4">
                        <span class="text-gray-500 text-xs uppercase">Cabang</span>
                        <span class="font-bold text-white"><?= $data['booking']->branch_name ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-800 pb-4">
                        <span class="text-gray-500 text-xs uppercase">Meja</span>
                        <span class="font-bold text-white">MEJA <?= $data['booking']->table_number ?></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-800 pb-4">
                        <span class="text-gray-500 text-xs uppercase">Waktu</span>
                        <span class="font-bold text-white"><?= date('d M Y, H:i', strtotime($data['booking']->start_time)) ?></span>
                    </div>
                    <div class="flex justify-between pt-4">
                        <span class="text-gray-500 text-xs uppercase">Total Bayar</span>
                        <span class="text-2xl font-black text-white">Rp <?= number_format($data['booking']->total_price, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>

            <!-- KANAN: INSTRUKSI PEMBAYARAN (QRIS) -->
            <div class="bg-white p-8 rounded-sm text-black">
                <h3 class="text-xl font-bold mb-6 uppercase tracking-tighter text-center">Scan QRIS Untuk Bayar</h3>
                
                <!-- Gambar QRIS -->
                <div class="bg-gray-100 p-4 mb-8">
                    <img src="<?= BASEURL ?>/assets/img/qris-bille.png" class="w-full grayscale hover:grayscale-0 transition-all duration-500" alt="QRIS Bille">
                </div>

                <div class="space-y-4">
                    <p class="text-[10px] text-gray-500 text-center uppercase leading-relaxed">
                        Silakan scan dan bayar menggunakan aplikasi E-Wallet atau M-Banking Anda. 
                        Simpan bukti transfer untuk konfirmasi.
                    </p>

                    <!-- Tombol WA Dinamis -->
                    <?php 
                        $pesanWA = "Halo Bille " . $data['booking']->branch_name . ", saya ingin konfirmasi pembayaran untuk Kode Booking: " . $data['booking']->booking_code . ".";
                        $linkWA = "https://wa.me/" . $data['booking']->phone_wa . "?text=" . urlencode($pesanWA);
                    ?>
                    <a href="<?= $linkWA ?>" target="_blank" class="block w-full bg-black text-white text-center py-4 font-bold tracking-widest hover:bg-gray-800 transition">
                        KONFIRMASI VIA WHATSAPP
                    </a>

                    <p class="text-[9px] text-center text-gray-400 mt-4">Pesanan akan otomatis dibatalkan jika tidak dibayar dalam 30 menit.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once '../app/views/templates/footer.php'; ?>