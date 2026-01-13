<?php
class Events extends Controller {

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
        $data['judul'] = 'Event Management - Bille Billiards';
        
        $this->view('admin/events/index', $data);
    }
}