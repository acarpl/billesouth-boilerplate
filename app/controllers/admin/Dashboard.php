<?php
class Dashboard extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
    }

    private function checkAdminAuth() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'super_admin' && $_SESSION['user_role'] !== 'branch_admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Admin Dashboard - Bille Billiards';

        // Load models
        $bookingModel = $this->model('Booking_model');
        $productModel = $this->model('Product_model');
        $tableModel = $this->model('Table_model');
        $branchModel = $this->model('Branch_model');

        // Get statistics for dashboard
        $data['total_bookings'] = $bookingModel->getTotalBookings();
        $data['total_products'] = $productModel->getTotalProducts();
        $data['total_tables'] = $tableModel->getTotalTables();
        $data['total_branches'] = $branchModel->getTotalBranches();

        // Get recent bookings
        $data['recent_bookings'] = $bookingModel->getRecentBookings(5);

        // Get active tables
        $data['active_tables'] = $tableModel->getActiveTables();

        $this->view('admin/dashboard', $data);
    }
}