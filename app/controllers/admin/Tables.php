<?php
class Tables extends Controller {

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
        $tableModel = $this->model('Table_model');
        $branchModel = $this->model('Branch_model');
        
        $data['judul'] = 'Table Management - Bille Billiards';
        $data['tables'] = $tableModel->getAll();
        $data['branches'] = $branchModel->getAll();
        
        $this->view('templates/header', $data);
        $this->view('admin/tables/index', $data);
        $this->view('templates/footer');
    }

    public function create() {
        $branchModel = $this->model('Branch_model');
        
        $data['judul'] = 'Add New Table - Bille Billiards';
        $data['branches'] = $branchModel->getAll();
        
        $this->view('templates/header', $data);
        $this->view('admin/tables/create', $data);
        $this->view('templates/footer');
    }

    public function store() {
        $tableModel = $this->model('Table_model');
        
        // Validate input
        if(empty($_POST['table_number']) || empty($_POST['branch_id']) || empty($_POST['capacity'])) {
            Flasher::setFlash('error', 'Table number, branch, and capacity are required');
            header('Location: ' . BASEURL . '/admin/tables/create');
            exit;
        }
        
        $data = [
            'table_number' => $_POST['table_number'],
            'branch_id' => $_POST['branch_id'],
            'capacity' => $_POST['capacity'],
            'status' => $_POST['status'] ?? 'available',
            'type' => $_POST['type'] ?? 'regular',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if($tableModel->create($data)) {
            Flasher::setFlash('success', 'Table added successfully');
        } else {
            Flasher::setFlash('error', 'Failed to add table');
        }
        
        header('Location: ' . BASEURL . '/admin/tables');
        exit;
    }

    public function edit($id) {
        $tableModel = $this->model('Table_model');
        $branchModel = $this->model('Branch_model');
        
        $data['judul'] = 'Edit Table - Bille Billiards';
        $data['table'] = $tableModel->getById($id);
        $data['branches'] = $branchModel->getAll();
        
        if(!$data['table']) {
            Flasher::setFlash('error', 'Table not found');
            header('Location: ' . BASEURL . '/admin/tables');
            exit;
        }
        
        $this->view('templates/header', $data);
        $this->view('admin/tables/edit', $data);
        $this->view('templates/footer');
    }

    public function update($id) {
        $tableModel = $this->model('Table_model');
        
        // Validate input
        if(empty($_POST['table_number']) || empty($_POST['branch_id']) || empty($_POST['capacity'])) {
            Flasher::setFlash('error', 'Table number, branch, and capacity are required');
            header('Location: ' . BASEURL . '/admin/tables/edit/' . $id);
            exit;
        }
        
        $data = [
            'table_number' => $_POST['table_number'],
            'branch_id' => $_POST['branch_id'],
            'capacity' => $_POST['capacity'],
            'status' => $_POST['status'],
            'type' => $_POST['type']
        ];
        
        if($tableModel->update($id, $data)) {
            Flasher::setFlash('success', 'Table updated successfully');
        } else {
            Flasher::setFlash('error', 'Failed to update table');
        }
        
        header('Location: ' . BASEURL . '/admin/tables');
        exit;
    }

    public function destroy($id) {
        $tableModel = $this->model('Table_model');
        
        if($tableModel->delete($id)) {
            Flasher::setFlash('success', 'Table deleted successfully');
        } else {
            Flasher::setFlash('error', 'Failed to delete table');
        }
        
        header('Location: ' . BASEURL . '/admin/tables');
        exit;
    }
}