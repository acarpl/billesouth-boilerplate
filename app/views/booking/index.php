<?php require_once '../app/views/templates/header.php'; ?>

<section class="min-h-screen bg-black pt-32 pb-20">
    <div class="max-w-7xl mx-auto px-4">
        
        <!-- Header Reservasi Dinamis -->
        <div class="mb-12 border-l-4 border-white pl-6">
            <h2 class="text-gray-500 tracking-widest text-xs uppercase mb-2">Langkah 1</h2>
            <h1 class="text-4xl font-bold uppercase tracking-tighter">Pilih Meja & Waktu</h1>
            <!-- Mengambil Nama Cabang dari Database -->
            <p class="text-gray-400 text-sm mt-2">Cabang: <span class="text-white font-bold"><?= $data['branch']->branch_name; ?></span></p>
        </div>

        <form action="<?= BASEURL; ?>/booking/checkout" method="POST" id="bookingForm">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- Sisi Kiri: Denah Meja (Dinamis) -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-900/50 border border-gray-800 p-8 rounded-sm">
                        <!-- Legend -->
                        <div class="flex gap-6 mb-10 text-[10px] tracking-widest uppercase">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-white"></div> <span>Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-gray-700"></div> <span>Terisi / Maintenance</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-emerald-500"></div> <span>Pilihanmu</span>
                            </div>
                        </div>

                        <!-- Table Grid Layout Dinamis -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <?php foreach($data['tables'] as $table) : 
                                // Cek status meja dari database
                                $isAvailable = ($table->status == 'Available');
                            ?>
                                <div class="relative group">
                                    <input type="radio" name="table_id" value="<?= $table->id ?>" 
                                           id="table-<?= $table->id ?>" class="hidden peer" 
                                           <?= (!$isAvailable) ? 'disabled' : '' ?> required>
                                           
                                    <label for="table-<?= $table->id ?>" 
                                        class="flex flex-col items-center justify-center aspect-square border-2 transition-all duration-300 
                                        <?= ($isAvailable) 
                                            ? 'bg-transparent border-gray-700 hover:border-white cursor-pointer peer-checked:bg-emerald-500 peer-checked:border-emerald-500' 
                                            : 'bg-gray-800 border-gray-800 opacity-30 cursor-not-allowed' ?>">
                                        
                                        <span class="text-[10px] font-bold mb-1"><?= strtoupper($table->type); ?></span>
                                        <span class="text-2xl font-bold"><?= $table->table_number; ?></span>
                                        
                                        <?php if($isAvailable) : ?>
                                            <span class="text-[9px] mt-2 text-gray-400 uppercase">Rp <?= number_format($table->price_per_hour, 0, ',', '.'); ?></span>
                                        <?php else : ?>
                                            <span class="text-[9px] mt-2 text-red-500 uppercase"><?= $table->status; ?></span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-12 p-6 border border-dashed border-gray-700 text-center">
                            <p class="text-gray-500 text-[10px] tracking-[0.3em] uppercase italic">Pilih meja yang tersedia untuk melanjutkan</p>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Form Waktu & Ringkasan -->
                <div class="lg:col-span-1">
                    <div class="bg-white text-black p-8 sticky top-32">
                        <h3 class="text-xl font-bold mb-8 uppercase tracking-tighter">Detail Reservasi</h3>
                        
                        <div class="space-y-6">
                            <!-- Tanggal -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Pilih Tanggal</label>
                                <input type="date" name="date" required min="<?= date('Y-m-d'); ?>"
                                       class="w-full border-b-2 border-black py-2 focus:outline-none text-sm font-bold bg-transparent">
                            </div>

                            <!-- Jam Mulai -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Jam Mulai</label>
                                <select name="start_time" required class="w-full border-b-2 border-black py-2 focus:outline-none text-sm font-bold bg-transparent">
                                    <?php for($h=10; $h<=23; $h++) : ?>
                                        <option value="<?= $h ?>:00"><?= $h ?>:00</option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Durasi Dinamis -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Durasi (Jam)</label>
                                <div class="flex items-center gap-4">
                                    <button type="button" id="btnMin" class="w-10 h-10 border border-black flex items-center justify-center hover:bg-black hover:text-white transition">-</button>
                                    <input type="number" name="duration" id="durationInput" value="1" min="1" max="12" readonly class="w-10 text-center font-bold text-lg focus:outline-none">
                                    <button type="button" id="btnPlus" class="w-10 h-10 border border-black flex items-center justify-center hover:bg-black hover:text-white transition">+</button>
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <!-- Ringkasan Harga (Statis tampilan, Logic di JS bawah) -->
                            <div class="flex justify-between items-center py-2">
                                <span class="text-xs text-gray-500 uppercase tracking-widest">Informasi</span>
                                <span class="text-[10px] text-right text-gray-400 italic">Total otomatis dihitung saat pembayaran</span>
                            </div>

                            <button type="submit" class="w-full bg-black text-white py-4 font-bold tracking-widest hover:bg-gray-800 transition mt-4">
                                LANJUT KE PEMBAYARAN
                            </button>
                            
                            <p class="text-[9px] text-center text-gray-400 uppercase mt-4">Dengan mengklik tombol, Anda menyetujui syarat & ketentuan Bille.</p>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

<!-- JavaScript untuk Durasi & Interaksi Ringan -->
<script>
    const btnMin = document.getElementById('btnMin');
    const btnPlus = document.getElementById('btnPlus');
    const durationInput = document.getElementById('durationInput');

    btnPlus.addEventListener('click', () => {
        let val = parseInt(durationInput.value);
        if(val < 12) durationInput.value = val + 1;
    });

    btnMin.addEventListener('click', () => {
        let val = parseInt(durationInput.value);
        if(val > 1) durationInput.value = val - 1;
    });
</script>

<?php require_once '../app/views/templates/footer.php'; ?>