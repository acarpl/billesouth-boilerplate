<?php
class Product_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllProducts() {
        $this->db->query("SELECT * FROM products WHERE is_active = 1");
        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query("SELECT * FROM products WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getLatestProduct() {
        $this->db->query("SELECT * FROM products ORDER BY id DESC LIMIT 1");
        return $this->db->single();
    }
}