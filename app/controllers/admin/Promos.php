<?php
class Promos extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
    }

    private function checkAdminAuth() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $promoModel = $this->model('Promo_model');
        
        $data['judul'] = 'Promo Management - Bille Billiards';
        $data['promos'] = $promoModel->getAll();
        
        $this->view('templates/header', $data);
        $this->view('admin/promos/index', $data);
        $this->view('templates/footer');
    }

    public function create() {
        $data['judul'] = 'Add New Promo - Bille Billiards';
        
        $this->view('templates/header', $data);
        $this->view('admin/promos/create', $data);
        $this->view('templates/footer');
    }

    public function store() {
        $promoModel = $this->model('Promo_model');
        
        // Validate input
        if(empty($_POST['code']) || empty($_POST['discount_type']) || empty($_POST['discount_value']) || empty($_POST['valid_from']) || empty($_POST['valid_until'])) {
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
            'code' => strtoupper($_POST['code']),
            'description' => $_POST['description'] ?? '',
            'discount_type' => $_POST['discount_type'],
            'discount_value' => $_POST['discount_value'],
            'valid_from' => $_POST['valid_from'],
            'valid_until' => $_POST['valid_until'],
            'usage_limit' => $_POST['usage_limit'] ?? null,
            'used_count' => 0,
            'status' => $_POST['status'] ?? 'active',
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
        
        $this->view('templates/header', $data);
        $this->view('admin/promos/edit', $data);
        $this->view('templates/footer');
    }

    public function update($id) {
        $promoModel = $this->model('Promo_model');
        
        // Validate input
        if(empty($_POST['code']) || empty($_POST['discount_type']) || empty($_POST['discount_value']) || empty($_POST['valid_from']) || empty($_POST['valid_until'])) {
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
        
        $data = [
            'code' => strtoupper($_POST['code']),
            'description' => $_POST['description'],
            'discount_type' => $_POST['discount_type'],
            'discount_value' => $_POST['discount_value'],
            'valid_from' => $_POST['valid_from'],
            'valid_until' => $_POST['valid_until'],
            'usage_limit' => $_POST['usage_limit'] ?? null,
            'status' => $_POST['status']
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