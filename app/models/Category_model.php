<?php

class Category_model {
    private $table = 'categories';
    private $db;

    public function __construct() {
        // Pastikan class Database sudah ada di core
        $this->db = new Database;
    }

    // Fungsi untuk mengambil semua kategori produk (Merchandise & F&B)
    public function getAll() {
        $this->db->query("SELECT * FROM " . $this->table);
        return $this->db->resultSet();
    }

    // Fungsi tambahan jika ingin ambil kategori berdasarkan tipe
    public function getByType($type) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE type = :type");
        $this->db->bind('type', $type);
        return $this->db->resultSet();
    }
}