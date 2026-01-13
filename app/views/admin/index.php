<div class="p-8">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-bold tracking-tighter uppercase text-white">Monitoring Meja</h1>
            <p class="text-gray-500 text-sm uppercase tracking-widest">Cabang: Citra Raya Tangerang</p>
        </div>
        <div class="flex gap-4">
            <div class="bg-gray-900 border border-gray-800 p-4 rounded text-center min-w-[120px]">
                <span class="block text-xs text-gray-500 uppercase">Total Meja</span>
                <span class="text-2xl font-bold text-white"><?= count($data['tables']); ?></span>
            </div>
            <div class="bg-emerald-900/20 border border-emerald-500/50 p-4 rounded text-center min-w-[120px]">
                <span class="block text-xs text-emerald-500 uppercase">Tersedia</span>
                <span class="text-2xl font-bold text-emerald-500">
                    <?php 
                        $count = 0;
                        foreach($data['tables'] as $t) if($t->status == 'Available') $count++;
                        echo $count;
                    ?>
                </span>
            </div>
        </div>
    </div>

<div class="min-h-screen bg-black pt-24 pb-10 px-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header Dashboard -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-800 pb-8">
            <div>
                <h1 class="text-4xl font-bold tracking-tighter text-white uppercase">Monitoring Billing</h1>
                <p class="text-gray-500 text-xs tracking-[0.3em] uppercase mt-2">Cabang: Citra Raya Tangerang</p>
            </div>
            <div class="text-right mt-4 md:mt-0">
                <p class="text-gray-500 text-[10px] uppercase">Waktu Server</p>
                <p class="text-xl font-mono font-bold text-white"><?= date('H:i'); ?> <span class="text-xs text-gray-600">WIB</span></p>
            </div>
        </div>

        <!-- Grid Meja -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($data['tables'] as $table) : 
                $isActive = ($table->status == 'Occupied');
                $billing = $isActive ? $this->model('Billing_model')->getActiveBillingByTable($table->id) : null;
            ?>
            <div class="relative group rounded-sm border transition-all duration-500 <?= $isActive ? 'bg-red-950/20 border-red-500/50' : 'bg-gray-900 border-gray-800' ?>">
                
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex flex-col">
                            <span class="text-4xl font-black <?= $isActive ? 'text-red-500' : 'text-white/20' ?>"><?= $table->table_number ?></span>
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest"><?= $table->type ?></span>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="w-3 h-3 rounded-full <?= $isActive ? 'bg-red-500 animate-ping' : 'bg-gray-700' ?>"></div>
                            <span class="text-[9px] mt-2 font-bold uppercase <?= $isActive ? 'text-red-500' : 'text-gray-500' ?>">
                                <?= $isActive ? 'In Use' : 'Ready' ?>
                            </span>
                        </div>
                    </div>

                    <?php if($isActive && $billing) : ?>
                        <!-- Bagian Sesi Aktif -->
                        <div class="space-y-4 mb-8">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-1">Durasi Bermain</p>
                                <div class="text-3xl font-mono font-bold text-white billing-timer" 
                                     data-start="<?= $billing->start_time ?>">
                                     00:00:00
                                </div>
                            </div>
                            <div class="flex justify-between text-[10px] text-gray-400 uppercase">
                                <span>Mulai: <?= date('H:i', strtotime($billing->start_time)) ?></span>
                                <span>Rate: <?= number_format($table->price_per_hour/1000, 0) ?>k/h</span>
                            </div>
                        </div>
                        <a href="<?= BASEURL ?>/admin/stopBilling/<?= $table->id ?>" 
                           onclick="return confirm('Selesaikan sesi dan hitung pembayaran?')"
                           class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-3 text-xs font-bold uppercase tracking-widest transition">
                           Selesaikan Sesi
                        </a>
                    <?php else : ?>
                        <!-- Bagian Meja Kosong -->
                        <div class="py-10 text-center">
                            <p class="text-gray-600 text-[10px] uppercase tracking-widest italic">Meja Tersedia</p>
                        </div>
                        <a href="<?= BASEURL ?>/admin/startBilling/<?= $table->id ?>" 
                           class="block w-full bg-white hover:bg-gray-200 text-black text-center py-3 text-xs font-bold uppercase tracking-widest transition">
                           Mulai Sesi
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- JavaScript Timer Real-time -->
<script>
function updateTimers() {
    const timers = document.querySelectorAll('.billing-timer');
    timers.forEach(timer => {
        const startTime = new Date(timer.dataset.start).getTime();
        const now = new Date().getTime();
        const diff = now - startTime;

        if (diff < 0) return;

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        const hDisplay = hours < 10 ? "0" + hours : hours;
        const mDisplay = minutes < 10 ? "0" + minutes : minutes;
        const sDisplay = seconds < 10 ? "0" + seconds : seconds;

        timer.innerText = hDisplay + ":" + mDisplay + ":" + sDisplay;
    });
}

// Update setiap detik
setInterval(updateTimers, 1000);
// Jalankan langsung saat page load
updateTimers();
</script>
</div>