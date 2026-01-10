<?php

class Home extends Controller {
    public function index() {
    $data['judul'] = 'Bille Billiards - Home';
    $data['branches'] = $this->model('Branch_model')->getAllBranches();
    $data['featured_product'] = $this->model('Product_model')->getLatestProduct();
    // $data['posts'] = $this->model('Post_model')->getLatestPosts(3);
    
    $this->view('templates/header', $data);
    $this->view('home/index', $data);
    $this->view('templates/footer');
    }
}