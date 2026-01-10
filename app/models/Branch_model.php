<?php
class Branch_model {
    private $table = 'branches';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllBranches() {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE is_active = 1");
        return $this->db->resultSet();
    }

    public function getBranchById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
}