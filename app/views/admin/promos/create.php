<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul']; ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome (Ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-white">
<div class="flex">
    <!-- Sidebar -->
    <?php $this->view('templates/admin_sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-gray-950 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">Tambah Promo Baru</h1>
                <p class="text-gray-400">Masukkan detail promo di bawah ini</p>
            </div>

            <div class="bg-gray-900 rounded-lg p-6 border border-gray-800">
                <form action="<?= BASEURL; ?>/admin/promos/store" method="POST">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-300 mb-2">Cabang (opsional)</label>
                            <select name="branch_id" id="branch_id" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                                <option value="">Semua Cabang</option>
                                <?php
                                $branchModel = $this->model('Branch_model');
                                $branches = $branchModel->getAll();
                                foreach($branches as $branch): ?>
                                    <option value="<?= $branch->id ?>"><?= htmlspecialchars($branch->branch_name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-300 mb-2">Kode Promo</label>
                            <input type="text" name="code" id="code" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan kode promo (contoh: WELCOME10)" required>
                            <p class="mt-1 text-sm text-gray-500">Akan diubah menjadi huruf kapital secara otomatis</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="discount_type" class="block text-sm font-medium text-gray-300 mb-2">Jenis Diskon</label>
                                <select name="discount_type" id="discount_type" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                                    <option value="percentage">Persentase (%)</option>
                                    <option value="fixed">Jumlah Tetap (Rp)</option>
                                </select>
                            </div>

                            <div>
                                <label for="discount_value" class="block text-sm font-medium text-gray-300 mb-2">Nilai Diskon</label>
                                <input type="number" name="discount_value" id="discount_value" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan nilai diskon" required min="0">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="min_purchase" class="block text-sm font-medium text-gray-300 mb-2">Minimal Pembelian (Rp)</label>
                                <input type="number" name="min_purchase" id="min_purchase" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan jumlah minimal pembelian" value="0" min="0">
                            </div>

                            <div>
                                <label for="max_discount" class="block text-sm font-medium text-gray-300 mb-2">Maksimal Diskon (Rp)</label>
                                <input type="number" name="max_discount" id="max_discount" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan jumlah maksimal diskon" value="0" min="0">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Akhir</label>
                                <input type="date" name="end_date" id="end_date" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" required>
                            </div>
                        </div>

                        <div>
                            <label for="usage_limit" class="block text-sm font-medium text-gray-300 mb-2">Batas Penggunaan</label>
                            <input type="number" name="usage_limit" id="usage_limit" class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5" placeholder="Masukkan batas penggunaan (0 untuk tak terbatas)" value="0" min="0">
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-300 mb-2">Status Aktif</label>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" class="w-4 h-4 text-blue-600 bg-gray-800 border-gray-700 rounded focus:ring-blue-500" checked>
                                <label for="is_active" class="ml-2 text-sm text-gray-300">Promo aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            Simpan Promo
                        </button>
                        <a href="<?= BASEURL; ?>/admin/promos" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
// Set default dates to today and next month
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const nextMonth = new Date();
    nextMonth.setMonth(nextMonth.getMonth() + 1);
    const nextMonthStr = nextMonth.toISOString().split('T')[0];

    document.getElementById('valid_from').value = today;
    document.getElementById('valid_until').value = nextMonthStr;
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>