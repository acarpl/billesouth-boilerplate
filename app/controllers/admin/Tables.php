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

        // Branch admins can only create tables for their own branch
        if ($_SESSION['user_role'] === 'branch_admin' && isset($_SESSION['branch_id'])) {
            $data['branches'] = [$branchModel->getById($_SESSION['branch_id'])];
            $data['is_branch_admin'] = true;
        } else {
            $data['branches'] = $branchModel->getAll();
            $data['is_branch_admin'] = false;
        }

        $this->view('admin/tables/create', $data);
    }

    public function store() {
        $tableModel = $this->model('Table_model');

        // Validate input
        if(empty($_POST['table_number']) || empty($_POST['price_per_hour'])) {
            Flasher::setFlash('error', 'Table number and price per hour are required');
            header('Location: ' . BASEURL . '/admin/tables/create');
            exit;
        }

        // Branch admins can only create tables for their own branch
        if ($_SESSION['user_role'] === 'branch_admin' && isset($_SESSION['branch_id'])) {
            $branch_id = $_SESSION['branch_id'];
        } else {
            $branch_id = $_POST['branch_id'];
        }

        // Validate that the branch_id is valid if not a branch admin
        if ($_SESSION['user_role'] !== 'branch_admin') {
            if(empty($_POST['branch_id'])) {
                Flasher::setFlash('error', 'Branch selection is required');
                header('Location: ' . BASEURL . '/admin/tables/create');
                exit;
            }
        }

        $data = [
            'table_number' => $_POST['table_number'],
            'branch_id' => $branch_id,
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

        if(!$data['table']) {
            Flasher::setFlash('error', 'Table not found');
            header('Location: ' . BASEURL . '/admin/tables');
            exit;
        }

        // Check if the table belongs to the branch admin's branch
        if ($_SESSION['user_role'] === 'branch_admin' && isset($_SESSION['branch_id'])) {
            if ($data['table']->branch_id != $_SESSION['branch_id']) {
                Flasher::setFlash('error', 'You do not have permission to edit this table');
                header('Location: ' . BASEURL . '/admin/tables');
                exit;
            }
            $data['branches'] = [$branchModel->getById($_SESSION['branch_id'])];
            $data['is_branch_admin'] = true;
        } else {
            $data['branches'] = $branchModel->getAll();
            $data['is_branch_admin'] = false;
        }

        $this->view('admin/tables/edit', $data);
    }

    public function update($id) {
        $tableModel = $this->model('Table_model');

        // Validate input
        if(empty($_POST['table_number']) || empty($_POST['price_per_hour'])) {
            Flasher::setFlash('error', 'Table number and price per hour are required');
            header('Location: ' . BASEURL . '/admin/tables/edit/' . $id);
            exit;
        }

        // Get the table to check if it belongs to the branch admin's branch
        $table = $tableModel->getById($id);
        if (!$table) {
            Flasher::setFlash('error', 'Table not found');
            header('Location: ' . BASEURL . '/admin/tables');
            exit;
        }

        // Branch admins can only update tables in their own branch
        if ($_SESSION['user_role'] === 'branch_admin' && isset($_SESSION['branch_id'])) {
            if ($table->branch_id != $_SESSION['branch_id']) {
                Flasher::setFlash('error', 'You do not have permission to update this table');
                header('Location: ' . BASEURL . '/admin/tables');
                exit;
            }
            // For branch admins, always use the original branch_id (don't allow changing branch)
            $branch_id = $table->branch_id;
        } else {
            // Super admin can change the branch
            if(empty($_POST['branch_id'])) {
                Flasher::setFlash('error', 'Branch selection is required');
                header('Location: ' . BASEURL . '/admin/tables/edit/' . $id);
                exit;
            }
            $branch_id = $_POST['branch_id'];
        }

        $data = [
            'table_number' => $_POST['table_number'],
            'branch_id' => $branch_id,
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

        // Get the table to check if it belongs to the branch admin's branch
        $table = $tableModel->getById($id);
        if (!$table) {
            Flasher::setFlash('error', 'Table not found');
            header('Location: ' . BASEURL . '/admin/tables');
            exit;
        }

        // Branch admins can only delete tables in their own branch
        if ($_SESSION['user_role'] === 'branch_admin' && isset($_SESSION['branch_id'])) {
            if ($table->branch_id != $_SESSION['branch_id']) {
                Flasher::setFlash('error', 'You do not have permission to delete this table');
                header('Location: ' . BASEURL . '/admin/tables');
                exit;
            }
        }

        if($tableModel->delete($id)) {
            Flasher::setFlash('success', 'Table deleted successfully');
        } else {
            Flasher::setFlash('error', 'Failed to delete table');
        }

        header('Location: ' . BASEURL . '/admin/tables');
        exit;
    }
}