<?php
class Table_model {
    private $table = 'tables';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTablesByBranch($branch_id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE branch_id = :id");
        $this->db->bind('id', $branch_id);
        return $this->db->resultSet();
    }

    public function getTableById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
}