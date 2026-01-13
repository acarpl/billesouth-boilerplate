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
    public function billing_active($branch_id = null) {
        $tableModel = $this->model('Table_model');
        $branchModel = $this->model('Branch_model');

        // Ambil branch_id dari parameter URL atau dari GET request
        $url_branch_id = $branch_id;
        $get_branch_id = isset($_GET['branch_id']) ? $_GET['branch_id'] : null;

        // Jika user adalah branch admin, hanya tampilkan tabel untuk branch mereka
        if ($_SESSION['user_role'] === 'branch_admin') {
            // Pastikan branch admin tidak bisa mengakses branch lain, abaikan parameter apapun
            $effective_branch_id = $_SESSION['branch_id'] ?? null;

            // Jika branch_id belum diset, coba ambil dari database
            if (!$effective_branch_id && isset($_SESSION['user_id'])) {
                $userModel = $this->model('User_model');
                $user = $userModel->getUserById($_SESSION['user_id']);
                if ($user && isset($user->branch_id)) {
                    $effective_branch_id = $user->branch_id;
                    $_SESSION['branch_id'] = $user->branch_id; // Simpan ke session untuk penggunaan selanjutnya
                }
            }
        } else {
            // Untuk super admin, gunakan branch_id dari URL atau GET request
            $effective_branch_id = $url_branch_id ?: $get_branch_id;
        }

        $data['judul'] = 'Active Billing - Bille Billiards';
        $data['tables'] = $tableModel->getActiveTables($effective_branch_id);
        $data['branch_id'] = $effective_branch_id;

        // Hanya super admin yang bisa melihat semua cabang
        if ($_SESSION['user_role'] === 'super_admin') {
            $data['branches'] = $branchModel->getAll();
        } else {
            // Branch admin hanya bisa melihat cabang mereka sendiri
            if (isset($_SESSION['branch_id'])) {
                $data['branches'] = [$branchModel->getById($_SESSION['branch_id'])];
            } else {
                $data['branches'] = []; // Empty array if no branch_id is set
            }
        }

        $this->view('admin/billing_active', $data);
    }
}
    