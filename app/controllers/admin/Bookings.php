<?php
class Bookings extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
    }

    private function checkAdminAuth() {
        if(!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'super_admin' && $_SESSION['user_role'] !== 'branch_admin')) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
    $data['judul'] = 'Kelola Reservasi';

    $branch_id = ($_SESSION['user_role'] != 'super_admin') ? $_SESSION['branch_id'] : null;
    
    $data['bookings'] = $this->model('Booking_model')->getAll($branch_id);
    
    $this->view('templates/admin_header', $data);
    $this->view('admin/bookings/index', $data);
    $this->view('templates/admin_footer');
    }

    public function updateBookingStatus($code, $status) {
    if ($this->model('Booking_model')->updateStatusByCode($code, $status) > 0) {
        // Set Flash message jika ada
        header('Location: ' . BASEURL . '/admin/bookings');
    }
}

// Fungsi untuk menampilkan Invoice
public function invoice($code) {
    $data['judul'] = 'Invoice #' . $code;
    $data['booking'] = $this->model('Booking_model')->getBookingByCode($code);
    
    // Kita buat view khusus tanpa header/sidebar admin
    $this->view('admin/bookings/invoice', $data);
}
}