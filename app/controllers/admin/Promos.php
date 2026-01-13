<?php
class Promos extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
    }

    private function checkAdminAuth() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'super_admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $promoModel = $this->model('Promo_model');

        $data['judul'] = 'Promo Management - Bille Billiards';
        $data['promos'] = $promoModel->getAll();

        $this->view('admin/promos/index', $data);
    }

    public function create() {
        $data['judul'] = 'Add New Promo - Bille Billiards';
        
        $this->view('admin/promos/create', $data);
    }

    public function store() {
        $promoModel = $this->model('Promo_model');

        // Validate input
        if(empty($_POST['code']) || empty($_POST['discount_type']) || empty($_POST['discount_value']) || empty($_POST['start_date']) || empty($_POST['end_date'])) {
            Flasher::setFlash('error', 'Code, discount type, discount value, and validity dates are required');
            header('Location: ' . BASEURL . '/admin/promos/create');
            exit;
        }

        // Validate discount value based on type
        if($_POST['discount_type'] === 'percentage' && $_POST['discount_value'] > 100) {
            Flasher::setFlash('error', 'Percentage discount cannot exceed 100%');
            header('Location: ' . BASEURL . '/admin/promos/create');
            exit;
        }

        $data = [
            'branch_id' => $_POST['branch_id'] ?? null,
            'code' => strtoupper($_POST['code']),
            'discount_type' => $_POST['discount_type'],
            'discount_value' => $_POST['discount_value'],
            'min_purchase' => $_POST['min_purchase'] ?? 0,
            'max_discount' => $_POST['max_discount'] ?? 0,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'usage_limit' => $_POST['usage_limit'] ?? 0,
            'used_count' => 0,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if($promoModel->create($data)) {
            Flasher::setFlash('success', 'Promo added successfully');
        } else {
            Flasher::setFlash('error', 'Failed to add promo');
        }

        header('Location: ' . BASEURL . '/admin/promos');
        exit;
    }

    public function edit($id) {
        $promoModel = $this->model('Promo_model');
        
        $data['judul'] = 'Edit Promo - Bille Billiards';
        $data['promo'] = $promoModel->getById($id);
        
        if(!$data['promo']) {
            Flasher::setFlash('error', 'Promo not found');
            header('Location: ' . BASEURL . '/admin/promos');
            exit;
        }
        
        $this->view('admin/promos/edit', $data);
    }

    public function update($id) {
        $promoModel = $this->model('Promo_model');

        // Validate input
        if(empty($_POST['code']) || empty($_POST['discount_type']) || empty($_POST['discount_value']) || empty($_POST['start_date']) || empty($_POST['end_date'])) {
            Flasher::setFlash('error', 'Code, discount type, discount value, and validity dates are required');
            header('Location: ' . BASEURL . '/admin/promos/edit/' . $id);
            exit;
        }

        // Validate discount value based on type
        if($_POST['discount_type'] === 'percentage' && $_POST['discount_value'] > 100) {
            Flasher::setFlash('error', 'Percentage discount cannot exceed 100%');
            header('Location: ' . BASEURL . '/admin/promos/edit/' . $id);
            exit;
        }

        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $data = [
            'branch_id' => $_POST['branch_id'] ?? null,
            'code' => strtoupper($_POST['code']),
            'discount_type' => $_POST['discount_type'],
            'discount_value' => $_POST['discount_value'],
            'min_purchase' => $_POST['min_purchase'] ?? 0,
            'max_discount' => $_POST['max_discount'] ?? 0,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'usage_limit' => $_POST['usage_limit'] ?? 0,
            'is_active' => $isActive
        ];

        if($promoModel->update($id, $data)) {
            Flasher::setFlash('success', 'Promo updated successfully');
        } else {
            Flasher::setFlash('error', 'Failed to update promo');
        }

        header('Location: ' . BASEURL . '/admin/promos');
        exit;
    }

    public function destroy($id) {
        $promoModel = $this->model('Promo_model');
        
        if($promoModel->delete($id)) {
            Flasher::setFlash('success', 'Promo deleted successfully');
        } else {
            Flasher::setFlash('error', 'Failed to delete promo');
        }
        
        header('Location: ' . BASEURL . '/admin/promos');
        exit;
    }
}