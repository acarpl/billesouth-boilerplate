<?php
class Inventory extends Controller {

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
        $productModel = $this->model('Product_model');

        $data['judul'] = 'Inventory Management - Bille Billiards';
        // Get branch ID from session or default to 1
        $branch_id = $_SESSION['branch_id'] ?? 1;
        $data['products'] = $productModel->getAllForBranch($branch_id);

        $this->view('admin/inventory/index', $data);
    }
}