<?php
class Branch_model {
    private $table = 'branches';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTotalBranches() {
        $this->db->query("SELECT COUNT(*) as total FROM " . $this->table);
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    public function getAll() {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY branch_name ASC");
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO " . $this->table . " (branch_name, location, phone, opening_hours, created_at) VALUES (:branch_name, :location, :phone, :opening_hours, :created_at)");
        $this->db->bind('branch_name', $data['branch_name']);
        $this->db->bind('location', $data['location']);
        $this->db->bind('phone', $data['phone']);
        $this->db->bind('opening_hours', $data['opening_hours']);
        $this->db->bind('created_at', $data['created_at']);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function update($id, $data) {
        $this->db->query("UPDATE " . $this->table . " SET branch_name = :branch_name, location = :location, phone = :phone, opening_hours = :opening_hours WHERE id = :id");
        $this->db->bind('branch_name', $data['branch_name']);
        $this->db->bind('location', $data['location']);
        $this->db->bind('phone', $data['phone']);
        $this->db->bind('opening_hours', $data['opening_hours']);
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