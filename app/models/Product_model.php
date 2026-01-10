<?php
class Product_model {
    private $table = 'products';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTotalProducts() {
        $this->db->query("SELECT COUNT(*) as total FROM " . $this->table);
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    public function getAll() {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY name ASC");
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO " . $this->table . " (name, description, price, category, stock, image, status, created_at) VALUES (:name, :description, :price, :category, :stock, :image, :status, :created_at)");
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('category', $data['category']);
        $this->db->bind('stock', $data['stock']);
        $this->db->bind('image', $data['image']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('created_at', $data['created_at']);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function update($id, $data) {
        $this->db->query("UPDATE " . $this->table . " SET name = :name, description = :description, price = :price, category = :category, stock = :stock, image = :image, status = :status WHERE id = :id");
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('category', $data['category']);
        $this->db->bind('stock', $data['stock']);
        $this->db->bind('image', $data['image']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function delete($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}