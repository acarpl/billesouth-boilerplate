<?php

class Booking extends Controller {

    /**
     * Halaman Utama Reservasi
     * @param int $branch_id ID Cabang (Default 1 untuk Citra Raya)
     */
    public function index($branch_id = 1) {
        $data['judul'] = 'Reservasi Meja - Bille Billiards';
        
        // 1. Ambil data detail cabang yang dipilih
        $data['branch'] = $this->model('Branch_model')->getBranchById($branch_id);
        
        // 2. Ambil semua daftar meja di cabang tersebut
        $data['tables'] = $this->model('Table_model')->getTablesByBranch($branch_id);
        
        // 3. Kirim ke View
        $this->view('templates/header', $data);
        $this->view('booking/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Proses Checkout Reservasi (Handling POST)
     */
    public function checkout() {
        // Pastikan user sudah login sebelum checkout
        if (!isset($_SESSION['user_id'])) {
            echo "<script>alert('Silakan login terlebih dahulu!'); window.location='".BASEURL."/auth';</script>";
            exit;
        }

        // Ambil data dari form booking/index.php
        $table_id   = $_POST['table_id'];
        $date       = $_POST['date'];
        $start_time = $_POST['start_time'];
        $duration   = $_POST['duration'];

        // Hitung Total Harga (Logic sederhana: harga_meja * durasi)
        $table = $this->model('Table_model')->getTableById($table_id);
        $total_price = $table->price_per_hour * $duration;

        // Data untuk disimpan ke database
        $booking_data = [
            'user_id'     => $_SESSION['user_id'],
            'branch_id'   => $table->branch_id,
            'table_id'    => $table_id,
            'booking_code'=> 'BILLE' . time(), // Generate kode unik
            'start_time'  => $date . ' ' . $start_time . ':00',
            'duration'    => $duration,
            'total_price' => $total_price
        ];

        // Jalankan model untuk simpan data
        if ($this->model('Booking_model')->createBooking($booking_data) > 0) {
            // Jika berhasil, arahkan ke halaman pembayaran (checkout)
            header('Location: ' . BASEURL . '/booking/payment/' . $booking_data['booking_code']);
        } else {
            echo "<script>alert('Gagal membuat reservasi!'); window.history.back();</script>";
        }
    }

    /**
     * Halaman Pembayaran (Tampilkan QRIS)
     */
    public function payment($booking_code) {
        $data['judul'] = 'Pembayaran - Bille Billiards';
        $data['booking'] = $this->model('Booking_model')->getBookingByCode($booking_code);

        $this->view('templates/header', $data);
        $this->view('booking/payment', $data);
        $this->view('templates/footer');
    }
}