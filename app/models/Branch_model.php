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

    public function getAll() {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE is_active = 1");
        return $this->db->resultSet();
    }

    public function getBranchById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO " . $this->table . " (branch_name, address, phone_wa, maps_link, is_active, created_at) VALUES (:branch_name, :address, :phone_wa, :maps_link, 1, :created_at)");
        $this->db->bind('branch_name', $data['branch_name']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('phone_wa', $data['phone_wa']);
        $this->db->bind('maps_link', $data['maps_link']);
        $this->db->bind('created_at', $data['created_at']);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function update($id, $data) {
        $this->db->query("UPDATE " . $this->table . " SET branch_name = :branch_name, address = :address, phone_wa = :phone_wa, maps_link = :maps_link WHERE id = :id");
        $this->db->bind('branch_name', $data['branch_name']);
        $this->db->bind('address', $data['address']);
        $this->db->bind('phone_wa', $data['phone_wa']);
        $this->db->bind('maps_link', $data['maps_link']);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function getTotalBranches() {
        $this->db->query("SELECT COUNT(*) as total FROM " . $this->table . " WHERE is_active = 1");
        $result = $this->db->single();
        return $result->total;
    }

    public function delete($id) {
        $this->db->query("UPDATE " . $this->table . " SET is_active = 0 WHERE id = :id");
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}