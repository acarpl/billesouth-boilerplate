<?php
class Order_model {
    private $table = 'orders';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAll($branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT o.*, u.name as customer_name
                FROM " . $this->table . " o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.branch_id = :branch_id
                ORDER BY o.created_at DESC
            ");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT o.*, u.name as customer_name
                FROM " . $this->table . " o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
            ");
        }
        $this->db->execute();
        return $this->db->resultSet();
    }
    
    public function getById($id) {
        $this->db->query("
            SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone
            FROM " . $this->table . " o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = :id
        ");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getOrderItems($orderId) {
        $this->db->query("
            SELECT oi.*, p.name as product_name, p.image as product_image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ");
        $this->db->bind('order_id', $orderId);
        $this->db->execute();
        return $this->db->resultSet();
    }
    
    public function updateStatus($id, $status) {
        $this->db->query("UPDATE " . $this->table . " SET status = :status WHERE id = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
    
    public function create($data) {
        $this->db->query("
            INSERT INTO " . $this->table . "
            (user_id, branch_id, promo_id, order_number, total_price, grand_total, discount_amount, shipping_cont, status, payment_method, payment_status, shipping_address, created_at)
            VALUES (:user_id, :branch_id, :promo_id, :order_number, :total_price, :grand_total, :discount_amount, :shipping_cont, :status, :payment_method, :payment_status, :shipping_address, :created_at)
        ");
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('branch_id', $data['branch_id'] ?? null);
        $this->db->bind('promo_id', $data['promo_id'] ?? null);
        $this->db->bind('order_number', $data['order_number'] ?? uniqid('ORD'));
        $this->db->bind('total_price', $data['total_amount'] ?? 0); // Map total_amount from input to total_price in DB
        $this->db->bind('grand_total', $data['grand_total'] ?? $data['total_amount'] ?? 0);
        $this->db->bind('discount_amount', $data['discount_amount'] ?? 0);
        $this->db->bind('shipping_cont', $data['shipping_cost'] ?? $data['shipping_cont'] ?? 0);
        $this->db->bind('status', $data['status']);
        $this->db->bind('payment_method', $data['payment_method']);
        $this->db->bind('payment_status', $data['payment_status']);
        $this->db->bind('shipping_address', $data['shipping_address']);
        $this->db->bind('created_at', $data['created_at']);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}