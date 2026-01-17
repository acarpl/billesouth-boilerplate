<?php

class Admin extends Controller {
    public function __construct() {
        // Keamanan: Hanya admin yang boleh masuk
        // Pastikan session sudah dimulai
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] == 'member') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $data['judul'] = 'Dashboard Billing - Bille';

        // Check if user is super admin to allow branch filtering
        $user_role = $_SESSION['user_role'] ?? 'member';
        $selected_branch_id = 1; // Default branch
        $use_all_branches = false;

        // Allow super admin to select branch via GET parameter
        if ($user_role === 'super_admin') {
            if (isset($_GET['branch_id']) && !empty($_GET['branch_id']) && $_GET['branch_id'] != 'all' && $_GET['branch_id'] != '') {
                $selected_branch_id = (int)$_GET['branch_id'];
                $use_all_branches = false;
            } else {
                // If no branch is selected or empty, show data from all branches
                $use_all_branches = true;
            }
        } else {
            // For branch admins, use their assigned branch
            $selected_branch_id = $_SESSION['branch_id'] ?? $selected_branch_id;
            $use_all_branches = false;
        }

        // Get all branches for the filter dropdown
        $data['branches'] = $this->model('Branch_model')->getAllBranches();

        // Ambil data meja berdasarkan cabang yang dipilih atau semua cabang
        if ($use_all_branches) {
            $data['tables'] = $this->model('Table_model')->getAll();
            $data['total_booking'] = count($this->model('Booking_model')->getAllToday());
            $data['active_bookings_count'] = count($this->model('Booking_model')->getAllActiveBookings());

            // For total revenue, get revenue from all branches
            $db = new Database;
            $db->query("SELECT SUM(total_price) as total_revenue FROM bookings WHERE payment_status = 'Paid'");
            $result = $db->single();
            $data['total_revenue'] = $result->total_revenue ? $result->total_revenue : 0;

            $data['recent_bookings'] = $this->model('Booking_model')->getRecentBookings(5, null); // null means all branches
            $data['active_bookings'] = $this->model('Booking_model')->getAllActiveBookings();
            $data['cashier_tables'] = $this->model('Table_model')->getAll(); // all tables from all branches
            $data['promos'] = $this->model('Promo_model')->getAllPromos(); // all promos from all branches
        } else {
            // Ambil data meja berdasarkan cabang yang dipilih
            $data['tables'] = $this->model('Table_model')->getTablesByBranch($selected_branch_id);

            // Ambil ringkasan hari ini (Opsional untuk statistik)
            $data['total_booking'] = count($this->model('Booking_model')->getTodayBookings($selected_branch_id));

            // Ambil data tambahan untuk dashboard berdasarkan cabang yang dipilih
            $data['active_bookings_count'] = count($this->model('Booking_model')->getActiveBookings($selected_branch_id));

            // For total revenue, we'll get revenue for the selected branch
            $db = new Database;
            $db->query("SELECT SUM(total_price) as total_revenue FROM bookings WHERE payment_status = 'Paid' AND branch_id = :branch_id");
            $db->bind('branch_id', $selected_branch_id);
            $result = $db->single();
            $data['total_revenue'] = $result->total_revenue ? $result->total_revenue : 0;

            // Ambil booking terbaru berdasarkan cabang yang dipilih
            $data['recent_bookings'] = $this->model('Booking_model')->getRecentBookings(5, $selected_branch_id);

            // Ambil data untuk antarmuka kasir (jika perlu)
            $data['active_bookings'] = $this->model('Booking_model')->getActiveBookings($selected_branch_id);
            $data['cashier_tables'] = $this->model('Table_model')->getTablesByBranch($selected_branch_id);
            $data['promos'] = $this->model('Promo_model')->getActivePromosByBranch($selected_branch_id);
        }

        // For members count, we'll get all members (this might be global or per branch depending on business logic)
        // For now, keeping it global as members might belong to system not specific branch
        $data['members_count'] = $this->model('User_model')->getMemberCount();

        // Pass selected branch ID to the view
        $data['selected_branch_id'] = $selected_branch_id;

        // $this->view('templates/header', $data);
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
            'user_id' => $_SESSION['user_id'] ?? null,
            'booking_id' => null, // Jika billing berasal dari booking, bisa ditambahkan nanti
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

    public function billing() {
        $data['judul'] = 'Daftar Billing - Bille';

        // Check if user is super admin to allow branch filtering
        $user_role = $_SESSION['user_role'] ?? 'member';
        $selected_branch_id = 1; // Default branch
        $use_all_branches = false;

        // Allow super admin to select branch via GET parameter
        if ($user_role === 'super_admin') {
            if (isset($_GET['branch_id']) && !empty($_GET['branch_id']) && $_GET['branch_id'] != 'all' && $_GET['branch_id'] != '') {
                $selected_branch_id = (int)$_GET['branch_id'];
                $use_all_branches = false;
            } else {
                // If no branch is selected or empty, show data from all branches
                $use_all_branches = true;
            }
        } else {
            // For branch admins, use their assigned branch
            $selected_branch_id = $_SESSION['branch_id'] ?? $selected_branch_id;
            $use_all_branches = false;
        }

        // Get all branches for the filter dropdown
        $data['branches'] = $this->model('Branch_model')->getAllBranches();

        // Get active billings based on branch selection
        $data['active_billings'] = $this->model('Billing_model')->getActiveBillings($use_all_branches ? null : $selected_branch_id);

        $this->view('templates/header', $data);
        $this->view('admin/billing', $data);
        $this->view('templates/footer');
    }

    public function getTablePrice($table_id) {
        $table = $this->model('Table_model')->getTableById($table_id);
        if ($table) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'price' => $table->price_per_hour]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'price' => 0]);
        }
    }

   
}
