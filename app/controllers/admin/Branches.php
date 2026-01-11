<?php
class Branches extends Controller {

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
        $branchModel = $this->model('Branch_model');
        $data['judul'] = 'Branch Management - Bille Billiards';
        $data['branches'] = $branchModel->getAll();
        
        $this->view('admin/branches/index', $data);
    }

    public function create() {
        $data['judul'] = 'Add New Branch - Bille Billiards';
        
        $this->view('admin/branches/create', $data);
    }

    public function store() {
        $branchModel = $this->model('Branch_model');

        // Validate input
        if(empty($_POST['branch_name']) || empty($_POST['address']) || empty($_POST['phone_wa'])) {
            Flasher::setFlash('error', 'All fields are required');
            header('Location: ' . BASEURL . '/admin/branches/create');
            exit;
        }

        $data = [
            'branch_name' => $_POST['branch_name'],
            'address' => $_POST['address'],
            'phone_wa' => $_POST['phone_wa'],
            'maps_link' => $_POST['maps_link'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];

        if($branchModel->create($data)) {
            Flasher::setFlash('success', 'Branch added successfully');
        } else {
            Flasher::setFlash('error', 'Failed to add branch');
        }

        header('Location: ' . BASEURL . '/admin/branches');
        exit;
    }

    public function edit($id) {
        $branchModel = $this->model('Branch_model');
        $data['judul'] = 'Edit Branch - Bille Billiards';
        $data['branch'] = $branchModel->getById($id);

        if(!$data['branch']) {
            Flasher::setFlash('error', 'Branch not found');
            header('Location: ' . BASEURL . '/admin/branches');
            exit;
        }

        $this->view('admin/branches/edit', $data);
    }

    public function update($id) {
        $branchModel = $this->model('Branch_model');

        // Validate input
        if(empty($_POST['branch_name']) || empty($_POST['address']) || empty($_POST['phone_wa'])) {
            Flasher::setFlash('error', 'All fields are required');
            header('Location: ' . BASEURL . '/admin/branches/edit/' . $id);
            exit;
        }

        $data = [
            'branch_name' => $_POST['branch_name'],
            'address' => $_POST['address'],
            'phone_wa' => $_POST['phone_wa'],
            'maps_link' => $_POST['maps_link'] ?? ''
        ];

        if($branchModel->update($id, $data)) {
            Flasher::setFlash('success', 'Branch updated successfully');
        } else {
            Flasher::setFlash('error', 'Failed to update branch');
        }

        header('Location: ' . BASEURL . '/admin/branches');
        exit;
    }

    public function destroy($id) {
        $branchModel = $this->model('Branch_model');
        
        if($branchModel->delete($id)) {
            Flasher::setFlash('success', 'Branch deleted successfully');
        } else {
            Flasher::setFlash('error', 'Failed to delete branch');
        }
        
        header('Location: ' . BASEURL . '/admin/branches');
        exit;
    }
}