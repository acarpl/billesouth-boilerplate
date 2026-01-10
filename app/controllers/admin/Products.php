<?php
class Products extends Controller {

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
        $productModel = $this->model('Product_model');
        
        $data['judul'] = 'Product Management - Bille Billiards';
        $data['products'] = $productModel->getAll();
        
        $this->view('templates/header', $data);
        $this->view('admin/products/index', $data);
        $this->view('templates/footer');
    }

    public function create() {
        $data['judul'] = 'Add New Product - Bille Billiards';
        
        $this->view('templates/header', $data);
        $this->view('admin/products/create', $data);
        $this->view('templates/footer');
    }

    public function store() {
        $productModel = $this->model('Product_model');
        
        // Validate input
        if(empty($_POST['name']) || empty($_POST['price']) || empty($_POST['category'])) {
            Flasher::setFlash('error', 'Product name, price, and category are required');
            header('Location: ' . BASEURL . '/admin/products/create');
            exit;
        }
        
        // Handle image upload if provided
        $image = '';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = 'public/uploads/products/';
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/billesouth-boilerplate/' . $uploadDir;
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadPath . $fileName;
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $image = $uploadDir . $fileName;
            }
        }
        
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'],
            'category' => $_POST['category'],
            'stock' => $_POST['stock'] ?? 0,
            'image' => $image,
            'status' => $_POST['status'] ?? 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if($productModel->create($data)) {
            Flasher::setFlash('success', 'Product added successfully');
        } else {
            Flasher::setFlash('error', 'Failed to add product');
        }
        
        header('Location: ' . BASEURL . '/admin/products');
        exit;
    }

    public function edit($id) {
        $productModel = $this->model('Product_model');
        
        $data['judul'] = 'Edit Product - Bille Billiards';
        $data['product'] = $productModel->getById($id);
        
        if(!$data['product']) {
            Flasher::setFlash('error', 'Product not found');
            header('Location: ' . BASEURL . '/admin/products');
            exit;
        }
        
        $this->view('templates/header', $data);
        $this->view('admin/products/edit', $data);
        $this->view('templates/footer');
    }

    public function update($id) {
        $productModel = $this->model('Product_model');
        
        // Validate input
        if(empty($_POST['name']) || empty($_POST['price']) || empty($_POST['category'])) {
            Flasher::setFlash('error', 'Product name, price, and category are required');
            header('Location: ' . BASEURL . '/admin/products/edit/' . $id);
            exit;
        }
        
        // Handle image upload if provided
        $image = $_POST['existing_image'] ?? '';
        if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = 'public/uploads/products/';
            $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/billesouth-boilerplate/' . $uploadDir;
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadPath . $fileName;
            
            if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Delete old image if exists
                if(!empty($_POST['existing_image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/billesouth-boilerplate/' . $_POST['existing_image'])) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/billesouth-boilerplate/' . $_POST['existing_image']);
                }
                $image = $uploadDir . $fileName;
            }
        }
        
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'category' => $_POST['category'],
            'stock' => $_POST['stock'],
            'image' => $image,
            'status' => $_POST['status']
        ];
        
        if($productModel->update($id, $data)) {
            Flasher::setFlash('success', 'Product updated successfully');
        } else {
            Flasher::setFlash('error', 'Failed to update product');
        }
        
        header('Location: ' . BASEURL . '/admin/products');
        exit;
    }

    public function destroy($id) {
        $productModel = $this->model('Product_model');
        
        // Get product to delete image if exists
        $product = $productModel->getById($id);
        
        if($productModel->delete($id)) {
            // Delete image file if exists
            if(!empty($product->image) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/billesouth-boilerplate/' . $product->image)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/billesouth-boilerplate/' . $product->image);
            }
            
            Flasher::setFlash('success', 'Product deleted successfully');
        } else {
            Flasher::setFlash('error', 'Failed to delete product');
        }
        
        header('Location: ' . BASEURL . '/admin/products');
        exit;
    }
}