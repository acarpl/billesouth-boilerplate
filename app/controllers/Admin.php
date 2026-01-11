<?php
class Admin extends Controller {
    public function __construct() {
        // Proteksi Admin: Hanya yang punya role admin boleh masuk
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] == 'member') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $branch_id = $_SESSION['branch_id'] ?? 1; // Default ke Citra Raya
        $data['judul'] = 'Dashboard Admin Bille';
        $data['tables'] = $this->model('Table_model')->getTablesByBranch($branch_id);

        // Ambil data statistik dari model
        $bookingModel = $this->model('Booking_model');
        $userModel = $this->model('User_model');

        $data['active_bookings_count'] = count($bookingModel->getActiveBookings($branch_id));
        $data['total_revenue'] = $bookingModel->getTotalRevenue();
        $data['members_count'] = count($userModel->getAllMembers());
        $data['recent_bookings'] = $bookingModel->getRecentBookings(5, $branch_id);

        $this->view('admin/index', $data);
    }
}