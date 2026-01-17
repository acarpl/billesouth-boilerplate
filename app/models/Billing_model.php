<?php
class Billing_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Memulai sesi baru
    public function startSession($data) {
        $query = "INSERT INTO billings (branch_id, table_id, user_id, booking_id, billing_number, start_time, duration_type, status, payment_status)
                  VALUES (:branch_id, :table_id, :user_id, :booking_id, :billing_number, :start_time, :duration_type, 'Active', 'Unpaid')";

        $this->db->query($query);
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('table_id', $data['table_id']);
        $this->db->bind('user_id', $data['user_id'] ?? null);
        $this->db->bind('booking_id', $data['booking_id'] ?? null);
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

    // Mengambil semua billing aktif
    public function getActiveBillings($branch_id = null) {
        if ($branch_id !== null) {
            $this->db->query("SELECT * FROM billings WHERE branch_id = :branch_id AND status = 'Active'");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("SELECT * FROM billings WHERE status = 'Active'");
        }
        return $this->db->resultSet();
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

    // Mengambil semua billing (aktif dan selesai)
    public function getAllBillings($branch_id = null) {
        if ($branch_id) {
            $this->db->query("SELECT * FROM billings WHERE branch_id = :branch_id ORDER BY id DESC");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("SELECT * FROM billings ORDER BY id DESC");
        }
        return $this->db->resultSet();
    }

    // Mengambil billing berdasarkan ID
    public function getBillingById($id) {
        $this->db->query("SELECT * FROM billings WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
}