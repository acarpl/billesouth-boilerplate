<?php
class Promo_model {
    private $table = 'promos';
    private $db;

    public function __construct() {
        $this->db = new Database;
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
    
    public function getByCode($code) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE code = :code AND status = 'active'");
        $this->db->bind('code', $code);
        return $this->db->single();
    }
    
    public function create($data) {
        $this->db->query("INSERT INTO " . $this->table . " (code, description, discount_type, discount_value, valid_from, valid_until, usage_limit, used_count, status, created_at) VALUES (:code, :description, :discount_type, :discount_value, :valid_from, :valid_until, :usage_limit, :used_count, :status, :created_at)");
        $this->db->bind('code', $data['code']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('discount_type', $data['discount_type']);
        $this->db->bind('discount_value', $data['discount_value']);
        $this->db->bind('valid_from', $data['valid_from']);
        $this->db->bind('valid_until', $data['valid_until']);
        $this->db->bind('usage_limit', $data['usage_limit']);
        $this->db->bind('used_count', $data['used_count']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('created_at', $data['created_at']);
        
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
    
    public function update($id, $data) {
        $this->db->query("UPDATE " . $this->table . " SET code = :code, description = :description, discount_type = :discount_type, discount_value = :discount_value, valid_from = :valid_from, valid_until = :valid_until, usage_limit = :usage_limit, status = :status WHERE id = :id");
        $this->db->bind('code', $data['code']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('discount_type', $data['discount_type']);
        $this->db->bind('discount_value', $data['discount_value']);
        $this->db->bind('valid_from', $data['valid_from']);
        $this->db->bind('valid_until', $data['valid_until']);
        $this->db->bind('usage_limit', $data['usage_limit']);
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
    
    public function incrementUsage($id) {
        $this->db->query("UPDATE " . $this->table . " SET used_count = used_count + 1 WHERE id = :id");
        $this->db->bind('id', $id);
        
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}