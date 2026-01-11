<?php
class Orders extends Controller {

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
        $orderModel = $this->model('Order_model');

        $data['judul'] = 'Order Management - Bille Billiards';

        // Get branch ID from session for branch admins, super admins can see all
        $branch_id = null;
        if ($_SESSION['user_role'] === 'branch_admin') {
            $branch_id = $_SESSION['branch_id'] ?? null;
        }

        $data['orders'] = $orderModel->getAll($branch_id);

        $this->view('admin/orders/index', $data);
    }

    public function show($id) {
        $orderModel = $this->model('Order_model');

        $data['judul'] = 'Order Details - Bille Billiards';

        // Get branch ID from session for branch admins, super admins can see all
        $branch_id = null;
        if ($_SESSION['user_role'] === 'branch_admin') {
            $branch_id = $_SESSION['branch_id'] ?? null;
        }

        $data['order'] = $orderModel->getById($id);

        // Check if the order belongs to the current branch for branch admins
        if ($_SESSION['user_role'] === 'branch_admin' && $branch_id && $data['order']->branch_id != $branch_id) {
            Flasher::setFlash('error', 'Order not found');
            header('Location: ' . BASEURL . '/admin/orders');
            exit;
        }

        $data['order_items'] = $orderModel->getOrderItems($id);

        if(!$data['order']) {
            Flasher::setFlash('error', 'Order not found');
            header('Location: ' . BASEURL . '/admin/orders');
            exit;
        }

        $this->view('admin/orders/show', $data);
    }

    public function updateStatus($id) {
        $orderModel = $this->model('Order_model');

        // Get branch ID from session for branch admins, super admins can see all
        $branch_id = null;
        if ($_SESSION['user_role'] === 'branch_admin') {
            $branch_id = $_SESSION['branch_id'] ?? null;
        }

        // Check if the order belongs to the current branch for branch admins
        $order = $orderModel->getById($id);
        if ($_SESSION['user_role'] === 'branch_admin' && $branch_id && $order->branch_id != $branch_id) {
            Flasher::setFlash('error', 'Order not found');
            header('Location: ' . BASEURL . '/admin/orders');
            exit;
        }

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