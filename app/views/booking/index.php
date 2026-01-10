<?php require_once '../app/views/templates/header.php'; ?>

<section class="min-h-screen bg-black pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Header Reservasi -->
        <div class="mb-12 border-l-4 border-white pl-6">
            <h2 class="text-gray-500 tracking-widest text-xs uppercase mb-2">Langkah 1</h2>
            <h1 class="text-4xl font-bold uppercase tracking-tighter">Pilih Meja & Waktu</h1>
            <p class="text-gray-400 text-sm mt-2">Cabang: <span class="text-white font-bold">Citra Raya, Tangerang</span></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <!-- Sisi Kiri: Denah Meja (Interactive Grid) -->
            <div class="lg:col-span-2">
                <div class="bg-gray-900/50 border border-gray-800 p-8 rounded-sm">
                    <!-- Legend -->
                    <div class="flex gap-6 mb-10 text-[10px] tracking-widest uppercase">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-white"></div> <span>Tersedia</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-gray-700"></div> <span>Terisi</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-emerald-500"></div> <span>Pilihanmu</span>
                        </div>
                    </div>

                    <!-- Table Grid Layout -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <?php 
                        // Contoh Statis: Membuat 12 Meja
                        for($i=1; $i<=12; $i++) : 
                            $status = ($i == 3 || $i == 7) ? 'occupied' : 'available'; // Contoh meja 3 & 7 terisi
                        ?>
                            <div class="relative group">
                                <input type="radio" name="table_id" value="<?= $i ?>" id="table-<?= $i ?>" class="hidden peer" <?= ($status == 'occupied') ? 'disabled' : '' ?>>
                                <label for="table-<?= $i ?>" class="flex flex-col items-center justify-center aspect-square border-2 transition-all duration-300 cursor-pointer
                                    <?= ($status == 'occupied') 
                                        ? 'bg-gray-800 border-gray-800 opacity-30 cursor-not-allowed' 
                                        : 'bg-transparent border-gray-700 hover:border-white peer-checked:bg-emerald-500 peer-checked:border-emerald-500' ?>">
                                    <span class="text-xs font-bold mb-1">MEJA</span>
                                    <span class="text-2xl font-bold"><?= sprintf("%02d", $i) ?></span>
                                </label>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- VIP Section Hint -->
                    <div class="mt-12 p-6 border border-dashed border-gray-700 text-center">
                        <p class="text-gray-500 text-xs tracking-widest uppercase italic">Area VVIP & Private Room Tersedia di Lantai 2</p>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan: Form Waktu & Ringkasan -->
            <div class="lg:col-span-1">
                <div class="bg-white text-black p-8 sticky top-32">
                    <h3 class="text-xl font-bold mb-8 uppercase tracking-tighter">Detail Reservasi</h3>
                    
                    <form action="<?= BASEURL; ?>/booking/checkout" method="POST">
                        <div class="space-y-6">
                            <!-- Tanggal -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Pilih Tanggal</label>
                                <input type="date" class="w-full border-b-2 border-black py-2 focus:outline-none text-sm font-bold">
                            </div>

                            <!-- Jam Mulai -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Jam Mulai</label>
                                <select class="w-full border-b-2 border-black py-2 focus:outline-none text-sm font-bold bg-transparent">
                                    <option>10:00</option>
                                    <option>11:00</option>
                                    <option>12:00</option>
                                    <option>13:00</option>
                                    <option>14:00</option>
                                    <option>15:00</option>
                                    <!-- Dan seterusnya -->
                                </select>
                            </div>

                            <!-- Durasi -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Durasi (Jam)</label>
                                <div class="flex items-center gap-4">
                                    <button type="button" class="w-8 h-8 border border-black flex items-center justify-center">-</button>
                                    <span class="font-bold text-lg">1</span>
                                    <button type="button" class="w-8 h-8 border border-black flex items-center justify-center">+</button>
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <!-- Ringkasan Harga -->
                            <div class="flex justify-between items-center py-2">
                                <span class="text-xs text-gray-500">Harga Meja / Jam</span>
                                <span class="font-bold">Rp 50.000</span>
                            </div>
                            <div class="flex justify-between items-center text-xl font-black py-2 border-t border-black">
                                <span>TOTAL</span>
                                <span>Rp 50.000</span>
                            </div>

                            <button type="submit" class="w-full bg-black text-white py-4 font-bold tracking-widest hover:bg-gray-800 transition mt-4">
                                LANJUT KE PEMBAYARAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once '../app/views/templates/footer.php'; ?>