<?php
class Booking_model {
    private $table = 'bookings';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTotalBookings() {
        $this->db->query("SELECT COUNT(*) as total FROM " . $this->table);
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    public function getRecentBookings($limit = 5) {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind('limit', $limit, PDO::PARAM_INT);
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY created_at DESC");
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
}