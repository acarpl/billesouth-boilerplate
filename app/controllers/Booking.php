<?php

class Booking extends Controller {

    public function index($branch_id = 1) {
        $data['judul'] = 'Reservasi Meja - Bille Billiards';
        $data['branch'] = $this->model('Branch_model')->getBranchById($branch_id);
        $data['tables'] = $this->model('Table_model')->getTablesByBranch($branch_id);
        
        $this->view('templates/header', $data);
        $this->view('booking/index', $data);
        $this->view('templates/footer');
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }

        $table_id   = $_POST['table_id'];
        $date       = $_POST['date'];
        $start_time = $_POST['start_time'];
        $duration   = $_POST['duration'];

        // Cek ketersediaan lagi (Double Check)
        $isAvailable = $this->model('Booking_model')->checkAvailability($table_id, $date, $start_time, $duration);
        
        if (!$isAvailable) {
            echo "<script>alert('Maaf, meja baru saja dipesan di jam tersebut. Pilih waktu lain.'); window.history.back();</script>";
            exit;
        }

        $table = $this->model('Table_model')->getTableById($table_id);
        $total_price = $table->price_per_hour * $duration;

        // Siapkan Data (TERMASUK SUBTOTAL)
        $booking_data = [
            'user_id'     => $_SESSION['user_id'],
            'branch_id'   => $table->branch_id,
            'table_id'    => $table_id,
            'booking_code'=> 'BILLE' . strtoupper(substr(uniqid(), 7)),
            'start_time'  => $date . ' ' . $start_time . ':00',
            'duration'    => $duration,
            'subtotal'    => $total_price, // Nilai subtotal
            'total_price' => $total_price
        ];

        if ($this->model('Booking_model')->createBooking($booking_data) > 0) {
            header('Location: ' . BASEURL . '/booking/payment/' . $booking_data['booking_code']);
        } else {
            echo "<script>alert('Terjadi kesalahan sistem.'); window.history.back();</script>";
        }
    }

    public function payment($booking_code) {
        $data['judul'] = 'Pembayaran - Bille Billiards';
        $data['booking'] = $this->model('Booking_model')->getBookingByCode($booking_code);

        $this->view('templates/header', $data);
        $this->view('booking/payment', $data);
        $this->view('templates/footer');
    }
}