<?php
class Admin extends Controller
{
    public function __construct()
    {
        // Proteksi Admin: Hanya yang punya role admin boleh masuk
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] == 'member') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index()
    {
        $branch_id = $_SESSION['branch_id'] ?? 1; // Default ke Citra Raya
        $data['judul'] = 'Dashboard Admin Bille';
        $data['tables'] = $this->model('Table_model')->getTablesByBranch($branch_id);
        $branch_id = ($_SESSION['user_role'] != 'super_admin') ? $_SESSION['branch_id'] : 1;
        $data['active_bookings'] = $this->model('Booking_model')->getActiveBookings($branch_id);

        // Ambil data statistik dari model
        $bookingModel = $this->model('Booking_model');
        $userModel = $this->model('User_model');

        $data['total_revenue'] = $this->model('Booking_model')->getTotalRevenue($branch_id);
        $data['total_bookings'] = $this->model('Booking_model')->getTotalBookings($branch_id);
        $data['members_count'] = count($userModel->getAllMembers());
        $data['recent_bookings'] = $this->model('Booking_model')->getRecentBookings($branch_id, 5);

        // Tambahkan data untuk cashier section (hanya untuk branch admin)
        if ($_SESSION['user_role'] === 'branch_admin') {
            $data['active_bookings'] = $bookingModel->getActiveBookings($branch_id);
            $data['cashier_tables'] = $this->model('Table_model')->getTablesByBranch($branch_id);
            $data['promos'] = $this->model('Promo_model')->getActivePromosByBranch($branch_id);
        } else {
            // Untuk super admin, bisa menampilkan data dari semua branches atau default
            $data['active_bookings'] = $bookingModel->getActiveBookings(); // tanpa branch_id untuk semua cabang
            $data['cashier_tables'] = $this->model('Table_model')->getTablesByBranch($branch_id);
            $data['promos'] = $this->model('Promo_model')->getActivePromosByBranch($branch_id);
        }

        $this->view('admin/index', $data);
    }

   
}
