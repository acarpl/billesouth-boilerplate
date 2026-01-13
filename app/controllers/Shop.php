<?php
class Shop extends Controller {
    public function index() {
        $data['judul'] = 'Toko Bille - Koleksi Eksklusif';
        $productModel = $this->model('Product_model');

        // Get branch ID from session or default to 1
        $branch_id = $_SESSION['branch_id'] ?? 1;
        $data['products'] = $productModel->getAllForBranch($branch_id);

        $this->view('templates/header', $data);
        $this->view('shop/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id) {
        $productModel = $this->model('Product_model');
        // Get branch ID from session or default to 1
        $branch_id = $_SESSION['branch_id'] ?? 1;

        // Get product with branch-specific stock info
        $data['product'] = $productModel->getById($id);
        $data['product']->stock = $productModel->getStock($id, $branch_id);

        $data['judul'] = $data['product']->name;

        $this->view('templates/header', $data);
        $this->view('shop/detail', $data);
        $this->view('templates/footer');
    }
}