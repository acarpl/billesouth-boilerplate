<?php
class Tables extends Controller {

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
        $tableModel = $this->model('Table_model');
        $branchModel = $this->model('Branch_model');

        $data['judul'] = 'Table Management - Bille Billiards';
        // Get branch ID from session or default to 1
        $branch_id = $_SESSION['branch_id'] ?? 1;
        $data['tables'] = $tableModel->getTablesByBranch($branch_id);
        $data['branches'] = $branchModel->getAll();

        $this->view('admin/tables/index', $data);
    }

    public function create() {
        $branchModel = $this->model('Branch_model');
        
        $data['judul'] = 'Add New Table - Bille Billiards';
        $data['branches'] = $branchModel->getAll();
        
        $this->view('admin/tables/create', $data);
    }

    public function store() {
        $tableModel = $this->model('Table_model');

        // Validate input
        if(empty($_POST['table_number']) || empty($_POST['branch_id']) || empty($_POST['price_per_hour'])) {
            Flasher::setFlash('error', 'Table number, branch, and price per hour are required');
            header('Location: ' . BASEURL . '/admin/tables/create');
            exit;
        }

        $data = [
            'table_number' => $_POST['table_number'],
            'branch_id' => $_POST['branch_id'],
            'type' => $_POST['type'] ?? 'Regular',
            'price_per_hour' => $_POST['price_per_hour'],
            'status' => $_POST['status'] ?? 'Available'
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
        
        $this->view('admin/tables/edit', $data);
    }

    public function update($id) {
        $tableModel = $this->model('Table_model');

        // Validate input
        if(empty($_POST['table_number']) || empty($_POST['branch_id']) || empty($_POST['price_per_hour'])) {
            Flasher::setFlash('error', 'Table number, branch, and price per hour are required');
            header('Location: ' . BASEURL . '/admin/tables/edit/' . $id);
            exit;
        }

        $data = [
            'table_number' => $_POST['table_number'],
            'branch_id' => $_POST['branch_id'],
            'type' => $_POST['type'],
            'price_per_hour' => $_POST['price_per_hour'],
            'status' => $_POST['status']
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