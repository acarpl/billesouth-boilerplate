<?php
class Order_model {
    private $table = 'orders';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAll() {
        $this->db->query("
            SELECT o.*, u.name as customer_name, p.name as product_name
            FROM " . $this->table . " o
            LEFT JOIN users u ON o.user_id = u.id
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN products p ON oi.product_id = p.id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ");
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
            (user_id, total_amount, status, payment_method, payment_status, shipping_address, notes, created_at) 
            VALUES (:user_id, :total_amount, :status, :payment_method, :payment_status, :shipping_address, :notes, :created_at)
        ");
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('total_amount', $data['total_amount']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('payment_method', $data['payment_method']);
        $this->db->bind('payment_status', $data['payment_status']);
        $this->db->bind('shipping_address', $data['shipping_address']);
        $this->db->bind('notes', $data['notes']);
        $this->db->bind('created_at', $data['created_at']);
        
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}