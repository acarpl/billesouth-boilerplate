<?php
class Promo_model {
    private $table = 'promos';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getAll() {
        $this->db->query("SELECT p.*, b.branch_name
                          FROM " . $this->table . " p
                          LEFT JOIN branches b ON p.branch_id = b.id
                          ORDER BY p.created_at DESC");
        $this->db->execute();
        return $this->db->resultSet();
    }
    
    public function getById($id) {
        $this->db->query("SELECT p.*, b.branch_name
                          FROM " . $this->table . " p
                          LEFT JOIN branches b ON p.branch_id = b.id
                          WHERE p.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
    
    public function getByCode($code) {
        $this->db->query("SELECT p.*, b.branch_name
                          FROM " . $this->table . " p
                          LEFT JOIN branches b ON p.branch_id = b.id
                          WHERE p.code = :code AND p.is_active = 1");
        $this->db->bind('code', $code);
        return $this->db->single();
    }
    
    public function create($data) {
        $this->db->query("INSERT INTO " . $this->table . " (branch_id, code, discount_type, discount_value, min_purchase, max_discount, start_date, end_date, usage_limit, used_count, is_active, created_at) VALUES (:branch_id, :code, :discount_type, :discount_value, :min_purchase, :max_discount, :start_date, :end_date, :usage_limit, :used_count, :is_active, :created_at)");
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('code', $data['code']);
        $this->db->bind('discount_type', $data['discount_type']);
        $this->db->bind('discount_value', $data['discount_value']);
        $this->db->bind('min_purchase', $data['min_purchase']);
        $this->db->bind('max_discount', $data['max_discount']);
        $this->db->bind('start_date', $data['start_date']);
        $this->db->bind('end_date', $data['end_date']);
        $this->db->bind('usage_limit', $data['usage_limit']);
        $this->db->bind('used_count', $data['used_count']);
        $this->db->bind('is_active', $data['is_active']);
        $this->db->bind('created_at', $data['created_at']);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function update($id, $data) {
        $this->db->query("UPDATE " . $this->table . " SET branch_id = :branch_id, code = :code, discount_type = :discount_type, discount_value = :discount_value, min_purchase = :min_purchase, max_discount = :max_discount, start_date = :start_date, end_date = :end_date, usage_limit = :usage_limit, is_active = :is_active WHERE id = :id");
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('code', $data['code']);
        $this->db->bind('discount_type', $data['discount_type']);
        $this->db->bind('discount_value', $data['discount_value']);
        $this->db->bind('min_purchase', $data['min_purchase']);
        $this->db->bind('max_discount', $data['max_discount']);
        $this->db->bind('start_date', $data['start_date']);
        $this->db->bind('end_date', $data['end_date']);
        $this->db->bind('usage_limit', $data['usage_limit']);
        $this->db->bind('is_active', $data['is_active']);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
    
    public function delete($id) {
        $this->db->query("UPDATE " . $this->table . " SET is_active = 0 WHERE id = :id");
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