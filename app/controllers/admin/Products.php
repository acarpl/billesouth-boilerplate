<?php
class Products extends Controller {

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

        $data['judul'] = 'Manajemen Produk - Bille Billiards';
        // Get branch ID from session or default to 1
        $branch_id = $_SESSION['branch_id'] ?? 1;
        $data['products'] = $productModel->getAllForBranch($branch_id);

        $this->view('admin/products/index', $data);
    }

    public function create() {
        $data['judul'] = 'Tambah Produk Baru - Bille Billiards';

        $this->view('admin/products/create', $data);
    }

    public function store() {
        $productModel = $this->model('Product_model');

        // Validate input
        if(empty($_POST['name']) || empty($_POST['price']) || empty($_POST['category_id'])) {
            Flasher::setFlash('error', 'Nama produk, harga, dan kategori diperlukan');
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

        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $data = [
            'category_id' => $_POST['category_id'],
            'name' => $_POST['name'],
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'],
            'image' => $image,
            'is_active' => $isActive
        ];

        // Determine branch context for product creation
        $branch_context = null;
        if ($_SESSION['user_role'] === 'branch_admin' && isset($_SESSION['branch_id'])) {
            $branch_context = $_SESSION['branch_id'];
        }

        if($productModel->create($data, $branch_context)) {
            Flasher::setFlash('success', 'Produk berhasil ditambahkan');
        } else {
            Flasher::setFlash('error', 'Gagal menambahkan produk');
        }

        header('Location: ' . BASEURL . '/admin/products');
        exit;
    }

    public function edit($id) {
        $productModel = $this->model('Product_model');

        $data['judul'] = 'Edit Produk - Bille Billiards';
        $data['product'] = $productModel->getById($id);

        if(!$data['product']) {
            Flasher::setFlash('error', 'Produk tidak ditemukan');
            header('Location: ' . BASEURL . '/admin/products');
            exit;
        }

        $this->view('admin/products/edit', $data);
    }

    public function update($id) {
        $productModel = $this->model('Product_model');

        // Validate input
        if(empty($_POST['name']) || empty($_POST['price']) || empty($_POST['category_id'])) {
            Flasher::setFlash('error', 'Nama produk, harga, dan kategori diperlukan');
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

        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $data = [
            'category_id' => $_POST['category_id'],
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'image' => $image,
            'is_active' => $isActive
        ];

        if($productModel->update($id, $data)) {
            Flasher::setFlash('success', 'Produk berhasil diperbarui');
        } else {
            Flasher::setFlash('error', 'Gagal memperbarui produk');
        }

        header('Location: ' . BASEURL . '/admin/products');
        exit;
    }

    public function destroy($id) {
        $productModel = $this->model('Product_model');

        // Get product to delete image if exists
        $product = $productModel->getById($id);

        if(!$product) {
            Flasher::setFlash('error', 'Produk tidak ditemukan');
            header('Location: ' . BASEURL . '/admin/products');
            exit;
        }

        // For enhanced security, we could check if the product is associated with the branch admin's branch
        // But since products are shared across branches with different stock levels,
        // we'll allow both super admin and branch admin to delete products
        // (In a real-world scenario, you might want to check if the product has stock in other branches)

        if($productModel->delete($id)) {
            // Delete image file if exists
            if(!empty($product->image) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/billesouth-boilerplate/' . $product->image)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/billesouth-boilerplate/' . $product->image);
            }

            Flasher::setFlash('success', 'Produk berhasil dihapus');
        } else {
            Flasher::setFlash('error', 'Gagal menghapus produk');
        }

        header('Location: ' . BASEURL . '/admin/products');
        exit;
    }
}