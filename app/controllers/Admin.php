<?php

class Admin extends Controller {
    public function __construct() {
        // Keamanan: Hanya admin yang boleh masuk
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] == 'member') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Dashboard Billing - Bille';
        
        // Kita kunci di Cabang ID 1 (Citra Raya) untuk sekarang
        $branch_id = 1; 
        
        // Ambil data meja
        $data['tables'] = $this->model('Table_model')->getTablesByBranch($branch_id);
        
        // Ambil ringkasan hari ini (Opsional untuk statistik)
        $data['total_booking'] = count($this->model('Booking_model')->getTodayBookings($branch_id));

        $this->view('templates/header', $data);
        $this->view('admin/index', $data);
        $this->view('templates/footer');
    }

    // Fungsi untuk mengubah status meja (Start/Stop Billing)
    public function updateTableStatus($id, $status) {
        if ($this->model('Table_model')->updateStatus($id, $status) > 0) {
            header('Location: ' . BASEURL . '/admin');
        }
    }

    public function startBilling($table_id) {
    $data = [
        'branch_id' => $_SESSION['branch_id'] ?? 1,
        'table_id' => $table_id,
        'duration_type' => 'Open Time' // Default main terus sampai stop
    ];

    if ($this->model('Billing_model')->startSession($data) > 0) {
        $this->model('Table_model')->updateStatus($table_id, 'Occupied');
        header('Location: ' . BASEURL . '/admin');
    }
    }

    public function stopBilling($table_id) {
    $billing = $this->model('Billing_model')->getActiveBillingByTable($table_id);
    $table = $this->model('Table_model')->getTableById($table_id);

    // Hitung Selisih Menit
    $start = new DateTime($billing->start_time);
    $end = new DateTime();
    $diff = $start->diff($end);
    $minutes = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
    
    // Minimal bayar 1 jam (opsional) atau per menit
    $total_price = ($minutes / 60) * $table->price_per_hour;

    $this->model('Billing_model')->stopSession($billing->id, $total_price);
    $this->model('Table_model')->updateStatus($table_id, 'Available');
    
    header('Location: ' . BASEURL . '/admin');
    }
}