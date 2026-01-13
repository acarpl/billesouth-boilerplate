<?php
class Dashboard extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
    }

    private function checkAdminAuth() {
        if(!isset($_SESSION['user_id']) || (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'super_admin' && $_SESSION['user_role'] !== 'branch_admin')) {
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
        $promoModel = $this->model('Promo_model');

        // Determine branch ID based on user role
        $branch_id = null;
        if ($_SESSION['user_role'] === 'branch_admin') {
            $branch_id = $_SESSION['branch_id'] ?? null;

            // Jika branch_id tidak diatur untuk branch admin, coba ambil dari tabel users
            if (!$branch_id && isset($_SESSION['user_id'])) {
                $userModel = $this->model('User_model');
                $user = $userModel->getUserById($_SESSION['user_id']);
                if ($user && $user->branch_id) {
                    $branch_id = $user->branch_id;
                    $_SESSION['branch_id'] = $user->branch_id; // Simpan ke session
                }
            }
        }

        // Get statistics for dashboard
        $data['total_bookings'] = $bookingModel->getTotalBookings($branch_id);
        $data['total_products'] = $productModel->getTotalProducts();
        $data['total_tables'] = $tableModel->getTotalTables($branch_id);
        $data['total_branches'] = $branchModel->getTotalBranches();

        // Get recent bookings
        $data['recent_bookings'] = $bookingModel->getRecentBookings(5, $branch_id);

        // Get active tables
        $data['active_tables'] = $tableModel->getActiveTables($branch_id);

        // Include cashier-related data for branch admins
        if ($_SESSION['user_role'] === 'branch_admin') {
            $data['cashier_tables'] = $tableModel->getTablesByBranch($branch_id);
            $data['promos'] = $promoModel->getActivePromos();

            // Get additional cashier data
            $data['total_revenue'] = $bookingModel->getTotalRevenue();
            $data['active_bookings'] = $bookingModel->getActiveBookings($branch_id);
        }

        $this->view('admin/dashboard', $data);
    }

    public function bookTable() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin');
            exit;
        }

        $bookingModel = $this->model('Booking_model');
        $tableModel = $this->model('Table_model');

        // Get table info
        $table_id = $_POST['table_id'];
        $table = $tableModel->getTableById($table_id);

        // Get branch ID from session for branch admins
        $branch_id = $_SESSION['user_role'] === 'branch_admin' ? ($_SESSION['branch_id'] ?? 1) : $table->branch_id;

        // Validate if table is available
        $date = $_POST['date'];
        $start_time = $date . ' ' . $_POST['start_time'] . ':00';
        $duration = (int)$_POST['duration'];

        if (!$bookingModel->checkAvailability($table_id, $date, $_POST['start_time'], $duration)) {
            Flasher::setFlash('error', 'Table is not available for the selected time slot');
            header('Location: ' . BASEURL . '/admin');
            exit;
        }

        // Calculate total price
        $total_price = $table->price_per_hour * $duration;

        // Apply promo if selected
        $promo_discount = 0;
        $promo_id = null;
        if (isset($_POST['promo_id']) && !empty($_POST['promo_id'])) {
            $promoModel = $this->model('Promo_model');
            $promo = $promoModel->getById($_POST['promo_id']);

            if ($promo && $promo->is_active) {
                if ($promo->discount_type === 'percentage') {
                    $promo_discount = ($total_price * $promo->discount_value) / 100;
                } else { // fixed amount
                    $promo_discount = $promo->discount_value;
                }

                // Make sure discount doesn't exceed total price
                $promo_discount = min($promo_discount, $total_price);

                $promo_id = $promo->id;
            }
        }

        $final_price = $total_price - $promo_discount;

        // Create booking data for walk-in customer (no user account)
        $booking_data = [
            'user_id' => null, // Walk-in customer
            'branch_id' => $branch_id,
            'table_id' => $table_id,
            'booking_code' => 'WALKIN' . time(), // Walk-in booking code
            'start_time' => $start_time,
            'duration' => $duration,
            'total_price' => $final_price,
            'customer_name' => $_POST['customer_name'], // Store customer name for walk-ins
            'customer_phone' => $_POST['customer_phone'] ?? ''
        ];

        if ($bookingModel->createWalkInBooking($booking_data)) {
            Flasher::setFlash('success', 'Table booked successfully!');
            header('Location: ' . BASEURL . '/admin/payment/' . $booking_data['booking_code']);
        } else {
            Flasher::setFlash('error', 'Failed to book table');
            header('Location: ' . BASEURL . '/admin');
        }
        exit;
    }

    public function payment($booking_code) {
        $bookingModel = $this->model('Booking_model');

        $data['judul'] = 'Payment - Bille Billiards';
        $data['booking'] = $bookingModel->getBookingByCode($booking_code);

        if (!$data['booking']) {
            Flasher::setFlash('error', 'Booking not found');
            header('Location: ' . BASEURL . '/admin');
            exit;
        }

        $this->view('admin/payment', $data);
    }

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/admin');
            exit;
        }

        $booking_code = $_POST['booking_code'];
        $payment_method = $_POST['payment_method'];
        $payment_status = 'Paid'; // Since it's cash payment at cashier

        $bookingModel = $this->model('Booking_model');

        if ($bookingModel->updatePaymentStatus($booking_code, $payment_status, $payment_method)) {
            Flasher::setFlash('success', 'Payment processed successfully!');
            header('Location: ' . BASEURL . '/admin/receipt/' . $booking_code);
        } else {
            Flasher::setFlash('error', 'Failed to process payment');
            header('Location: ' . BASEURL . '/admin/payment/' . $booking_code);
        }
        exit;
    }

    public function receipt($booking_code) {
        $bookingModel = $this->model('Booking_model');
        $tableModel = $this->model('Table_model');

        $data['judul'] = 'Receipt - Bille Billiards';
        $data['booking'] = $bookingModel->getBookingByCode($booking_code);
        $data['table'] = $tableModel->getTableById($data['booking']->table_id);

        if (!$data['booking']) {
            Flasher::setFlash('error', 'Booking not found');
            header('Location: ' . BASEURL . '/admin');
            exit;
        }

        $this->view('admin/receipt', $data);
    }
}