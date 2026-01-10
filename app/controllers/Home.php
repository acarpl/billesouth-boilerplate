<?php

class Home extends Controller {
    public function index() {
        $data['judul'] = 'Bille Billiards - Home';
        $data['nama'] = 'Admin Bille';
        
        // Memanggil View
        $this->view('home/index', $data);
    }
}