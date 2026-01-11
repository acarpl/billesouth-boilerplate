<?php
class Inventory extends Controller {

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
        $productModel = $this->model('Product_model');

        $data['judul'] = 'Inventory Management - Bille Billiards';
        $data['products'] = $productModel->getAll();

        $this->view('admin/inventory/index', $data);
    }
}