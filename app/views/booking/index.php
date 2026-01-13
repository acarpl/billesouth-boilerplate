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

                    <!-- Table Grid Layout -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <?php
                        // Get active bookings to determine which tables are occupied
                        $bookingModel = $this->model('Booking_model');
                        $active_bookings = $bookingModel->getActiveBookings($data['branch']->id);

                        // Create an array of booked table IDs for quick lookup
                        $booked_table_ids = [];
                        foreach ($active_bookings as $booking) {
                            $booked_table_ids[] = $booking->table_id;
                        }

                        foreach ($data['tables'] as $table):
                            $is_booked = in_array($table->id, $booked_table_ids);
                            $table_type_lower = strtolower($table->type);

                            // Determine classes based on table type and availability
                            if ($table_type_lower === 'vvip') {
                                if ($is_booked) {
                                    $box_classes = "bg-gray-800 border-gray-800 opacity-30 cursor-not-allowed"; // VVIP booked: gray with low opacity
                                } else {
                                    $box_classes = "bg-purple-600 border-purple-600 hover:border-white peer-checked:bg-emerald-500 peer-checked:border-emerald-500"; // VVIP available: purple
                                }
                            } elseif ($table_type_lower === 'vip') {
                                if ($is_booked) {
                                    $box_classes = "bg-gray-800 border-gray-800 opacity-30 cursor-not-allowed"; // VIP booked: gray with low opacity
                                } else {
                                    $box_classes = "bg-yellow-500 text-gray-900 border-yellow-500 hover:border-white peer-checked:bg-emerald-500 peer-checked:border-emerald-500"; // VIP available: yellow
                                }
                            } else {
                                if ($is_booked) {
                                    $box_classes = "bg-gray-800 border-gray-800 opacity-30 cursor-not-allowed"; // Regular booked: gray with low opacity
                                } else {
                                    $box_classes = "bg-white text-black border-gray-700 hover:border-white peer-checked:bg-emerald-500 peer-checked:border-emerald-500"; // Regular available: white
                                }
                            }
                        ?>
                            <div class="relative group">
                                <input type="radio" name="table_id" value="<?= $table->id ?>" id="table-<?= $table->id ?>" class="hidden peer" <?= ($is_booked) ? 'disabled' : '' ?> required>
                                <label for="table-<?= $table->id ?>" class="flex flex-col items-center justify-center aspect-square border-2 transition-all duration-300 cursor-pointer
                                    <?= $box_classes ?>">
                                    <span class="text-xs font-bold mb-1">MEJA</span>
                                    <span class="text-2xl font-bold"><?= sprintf("%02d", $table->table_number) ?></span>
                                    <span class="text-[10px] mt-1"><?= ucfirst($table->type) ?></span>
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

                    <form action="<?= BASEURL; ?>/booking/checkout" method="POST">
                        <div class="space-y-6">
                            <!-- Tanggal -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Pilih Tanggal</label>
                                <input type="date" name="date" id="date" class="w-full border-b-2 border-black py-2 focus:outline-none text-sm font-bold" required>
                            </div>

                            <!-- Jam Mulai -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Jam Mulai</label>
                                <select name="start_time" id="start_time" class="w-full border-b-2 border-black py-2 focus:outline-none text-sm font-bold bg-transparent" required>
                                    <option value="">Pilih Jam</option>
                                    <?php for($hour = 10; $hour <= 23; $hour++): ?>
                                        <option value="<?= sprintf('%02d', $hour) ?>:00"><?= sprintf('%02d', $hour) ?>:00</option>
                                        <option value="<?= sprintf('%02d', $hour) ?>:30"><?= sprintf('%02d', $hour) ?>:30</option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Durasi Dinamis -->
                            <div>
                                <label class="block text-[10px] font-bold uppercase mb-2">Durasi (Jam)</label>
                                <div class="flex items-center gap-4">
                                    <button type="button" id="decrease-duration" class="w-8 h-8 border border-black flex items-center justify-center">-</button>
                                    <input type="number" name="duration" id="duration" value="1" min="1" max="12" class="font-bold text-lg w-12 text-center" readonly>
                                    <button type="button" id="increase-duration" class="w-8 h-8 border border-black flex items-center justify-center">+</button>
                                </div>
                            </div>

                            <hr class="border-gray-200">

                            <!-- Ringkasan Harga (Statis tampilan, Logic di JS bawah) -->
                            <div class="flex justify-between items-center py-2">
                                <span class="text-xs text-gray-500">Harga Meja / Jam</span>
                                <span id="price-per-hour" class="font-bold">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-xl font-black py-2 border-t border-black">
                                <span>TOTAL</span>
                                <span id="total-price">Rp 0</span>
                            </div>

                            <button type="submit" class="w-full bg-black text-white py-4 font-bold tracking-widest hover:bg-gray-800 transition mt-4" id="checkout-btn" disabled>
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

<script>
    // Store table data for quick access
    const tableData = {};
    <?php foreach($data['tables'] as $table): ?>
    tableData[<?= $table->id ?>] = {
        id: <?= $table->id ?>,
        number: <?= $table->table_number ?>,
        type: '<?= ucfirst($table->type) ?>',
        price: <?= $table->price_per_hour ?>
    };
    <?php endforeach; ?>

    // Duration controls
    const durationInput = document.getElementById('duration');
    const decreaseBtn = document.getElementById('decrease-duration');
    const increaseBtn = document.getElementById('increase-duration');
    const checkoutBtn = document.getElementById('checkout-btn');

    // Event listeners for duration buttons
    decreaseBtn.addEventListener('click', () => {
        let currentValue = parseInt(durationInput.value);
        if(currentValue > 1) {
            durationInput.value = currentValue - 1;
            updatePriceSummary();
        }
    });

    increaseBtn.addEventListener('click', () => {
        let currentValue = parseInt(durationInput.value);
        if(currentValue < 12) {
            durationInput.value = currentValue + 1;
            updatePriceSummary();
        }
    });

    // Listen for table selection
    const tableRadios = document.querySelectorAll('input[name="table_id"]');
    tableRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if(this.checked) {
                const tableId = parseInt(this.value);
                const tableInfo = tableData[tableId];

                if(tableInfo) {
                    // Update price display
                    document.getElementById('price-per-hour').textContent = 'Rp ' + tableInfo.price.toLocaleString('id-ID');

                    // Enable checkout button
                    checkoutBtn.disabled = false;

                    // Update total price
                    updatePriceSummary();
                }
            }
        });
    });

    // Listen for date and time changes
    document.getElementById('date').addEventListener('change', updatePriceSummary);
    document.getElementById('start_time').addEventListener('change', updatePriceSummary);
    durationInput.addEventListener('change', updatePriceSummary);

    // Function to update price summary
    function updatePriceSummary() {
        const selectedRadio = document.querySelector('input[name="table_id"]:checked');
        if(!selectedRadio) {
            document.getElementById('total-price').textContent = 'Rp 0';
            return;
        }

        const tableId = parseInt(selectedRadio.value);
        const tableInfo = tableData[tableId];
        if(!tableInfo) return;

        const duration = parseInt(durationInput.value) || 1;
        const totalPrice = tableInfo.price * duration;

        document.getElementById('total-price').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    }

    // Initialize date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').value = today;
</script>

<?php require_once '../app/views/templates/footer.php'; ?>