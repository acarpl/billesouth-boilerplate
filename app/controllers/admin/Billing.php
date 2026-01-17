<?php
class Billing extends Controller {

    public function __construct() {
        parent::__construct();
        // Proteksi Admin: Hanya yang punya role admin boleh masuk
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] == 'member') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        // Redirect to billing_active method
        $this->billing_active();
    }

    // Method untuk menampilkan halaman billing/active tables
    public function billing_active($branch_id = null)
    {
        $billingModel = $this->model('Billing_model');
        $branchModel  = $this->model('Branch_model');

        // Ambil branch dari URL atau GET
        $requested_branch = $branch_id ?? ($_GET['branch_id'] ?? null);

        /**
         * Tentukan branch yang aktif
         * -------------------------------------------
         * - Branch Admin  : hanya bisa melihat cabangnya sendiri
         * - Super Admin   : bebas pilih semua cabang
         */
        if ($_SESSION['user_role'] === 'branch_admin') {
            // Pakai branch_id milik user
            $effective_branch_id = $_SESSION['branch_id'] ?? null;

            // Jika belum ada, ambil dari database user
            if (!$effective_branch_id && isset($_SESSION['user_id'])) {
                $userModel = $this->model('User_model');
                $user      = $userModel->getUserById($_SESSION['user_id']);

                if ($user && !empty($user->branch_id)) {
                    $effective_branch_id       = $user->branch_id;
                    $_SESSION['branch_id']     = $user->branch_id; // simpan ke session
                }
            }
        } else {
            // Super admin bebas pilih
            $effective_branch_id = $requested_branch;
        }

        // View data
        $data = [
            'judul'           => 'Daftar Billing - Bille',
            'active_billings' => $billingModel->getActiveBillings($effective_branch_id),
            'all_billings'    => $billingModel->getAllBillings($effective_branch_id)
        ];

        // Ambil daftar cabang
        if ($_SESSION['user_role'] === 'super_admin') {
            $data['branches'] = $branchModel->getAll();
        } else {
            // Branch admin hanya cabangnya sendiri
            $data['branches'] = [];

            if (!empty($_SESSION['branch_id'])) {
                $data['branches'][] = $branchModel->getById($_SESSION['branch_id']);
            }
        }

        $this->view('admin/billing_active', $data);
    }


    public function detail($billing_id) {
        $billingModel = $this->model('Billing_model');
        $branchModel = $this->model('Branch_model');
        $tableModel = $this->model('Table_model');

        $billing = $billingModel->getBillingById($billing_id);
        if (!$billing) {
            // Jika billing tidak ditemukan, redirect ke halaman daftar billing
            header('Location: ' . BASEURL . '/billing');
            exit;
        }

        $branch = $branchModel->getById($billing->branch_id);
        $table = $tableModel->getById($billing->table_id);

        $data['judul'] = 'Detail Billing - Bille';
        $data['billing'] = $billing;
        $data['branch'] = $branch;
        $data['table'] = $table;

        $this->view('admin/billings/billing_detail', $data);
    }    

}
    