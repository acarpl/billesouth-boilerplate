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

    public function getAll() {
        $this->db->query("SELECT * FROM products WHERE is_active = 1 ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query("SELECT * FROM products WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM products WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getLatestProduct() {
        $this->db->query("SELECT * FROM products ORDER BY id DESC LIMIT 1");
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO products (category_id, name, description, price, image, is_active) VALUES (:category_id, :name, :description, :price, :image, :is_active)");
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('image', $data['image']);
        $this->db->bind('is_active', $data['is_active']);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function update($id, $data) {
        $this->db->query("UPDATE products SET category_id = :category_id, name = :name, description = :description, price = :price, image = :image, is_active = :is_active WHERE id = :id");
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('image', $data['image']);
        $this->db->bind('is_active', $data['is_active']);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function delete($id) {
        $this->db->query("UPDATE products SET is_active = 0 WHERE id = :id");
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}