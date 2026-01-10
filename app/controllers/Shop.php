<?php
class Shop extends Controller {
    public function index() {
        $data['judul'] = 'Toko Bille - Koleksi Eksklusif';
        $data['products'] = $this->model('Product_model')->getAllProducts();
        
        $this->view('templates/header', $data);
        $this->view('shop/index', $data);
        $this->view('templates/footer');
    }

    public function detail($id) {
        $data['product'] = $this->model('Product_model')->getProductById($id);
        $data['judul'] = $data['product']->name;

        $this->view('templates/header', $data);
        $this->view('shop/detail', $data);
        $this->view('templates/footer');
    }
}