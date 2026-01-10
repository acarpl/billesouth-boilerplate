<?php
class Orders extends Controller {

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
        $orderModel = $this->model('Order_model');
        
        $data['judul'] = 'Order Management - Bille Billiards';
        $data['orders'] = $orderModel->getAll();
        
        $this->view('templates/header', $data);
        $this->view('admin/orders/index', $data);
        $this->view('templates/footer');
    }

    public function show($id) {
        $orderModel = $this->model('Order_model');
        
        $data['judul'] = 'Order Details - Bille Billiards';
        $data['order'] = $orderModel->getById($id);
        $data['order_items'] = $orderModel->getOrderItems($id);
        
        if(!$data['order']) {
            Flasher::setFlash('error', 'Order not found');
            header('Location: ' . BASEURL . '/admin/orders');
            exit;
        }
        
        $this->view('templates/header', $data);
        $this->view('admin/orders/show', $data);
        $this->view('templates/footer');
    }

    public function updateStatus($id) {
        $orderModel = $this->model('Order_model');
        
        if(empty($_POST['status'])) {
            Flasher::setFlash('error', 'Status is required');
            header('Location: ' . BASEURL . '/admin/orders');
            exit;
        }
        
        $status = $_POST['status'];
        
        if($orderModel->updateStatus($id, $status)) {
            Flasher::setFlash('success', 'Order status updated successfully');
        } else {
            Flasher::setFlash('error', 'Failed to update order status');
        }
        
        header('Location: ' . BASEURL . '/admin/orders');
        exit;
    }
}