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
        
        $this->view('templates/header', $data);
        $this->view('admin/index', $data);
        $this->view('templates/footer');
    }
}