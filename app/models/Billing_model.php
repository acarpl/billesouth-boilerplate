<?php
class Billing_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Memulai sesi baru
    public function startSession($data) {
        $query = "INSERT INTO billings (branch_id, table_id, billing_number, start_time, duration_type, status) 
                  VALUES (:branch_id, :table_id, :billing_number, :start_time, :duration_type, 'Active')";
        
        $this->db->query($query);
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('table_id', $data['table_id']);
        $this->db->bind('billing_number', 'BILL-' . time());
        $this->db->bind('start_time', date('Y-m-d H:i:s'));
        $this->db->bind('duration_type', $data['duration_type']);
        
        $this->db->execute();
        return $this->db->rowCount();
    }

    // Mengambil data billing yang sedang aktif di meja tertentu
    public function getActiveBillingByTable($table_id) {
        $this->db->query("SELECT * FROM billings WHERE table_id = :table_id AND status = 'Active'");
        $this->db->bind('table_id', $table_id);
        return $this->db->single();
    }

    // Mengakhiri sesi dan hitung total
    public function stopSession($id, $total_price) {
        $query = "UPDATE billings SET end_time = :end_time, grand_total = :total, status = 'Finished', payment_status = 'Paid' 
                  WHERE id = :id";
        $this->db->query($query);
        $this->db->bind('end_time', date('Y-m-d H:i:s'));
        $this->db->bind('total', $total_price);
        $this->db->bind('id', $id);
        $this->db->execute();
    }
}