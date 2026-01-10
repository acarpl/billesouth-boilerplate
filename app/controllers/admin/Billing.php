<?php
class Billing extends Controller {

    public function __construct() {
        parent::__construct();
        // Authentication check can be added here
    }

    // Method untuk menampilkan halaman billing/active tables
    public function billing_active($branch_id = null) {
        $tableModel = $this->model('Table_model');
        $branchModel = $this->model('Branch_model');

        $data['judul'] = 'Active Billing - Bille Billiards';
        $data['tables'] = $tableModel->getActiveTables($branch_id);
        $data['branch_id'] = $branch_id;
        $data['branches'] = $branchModel->getAll();

        $this->view('templates/header', $data);
        $this->view('admin/billing_active', $data);
        $this->view('templates/footer');
    }
}
