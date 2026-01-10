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

    // Method untuk mendapatkan tabel aktif (sedang digunakan)
    public function getActiveTables($branch_id = null) {
        $sql = "SELECT t.*, b.name as branch_name,
                       CASE
                           WHEN bkt.id IS NOT NULL THEN 'occupied'
                           ELSE 'available'
                       END as table_status
                FROM " . $this->table . " t
                JOIN branches b ON t.branch_id = b.id
                LEFT JOIN bookings bkt ON t.id = bkt.table_id
                    AND bkt.payment_status = 'Paid'
                    AND bkt.start_time <= NOW()
                    AND bkt.end_time >= NOW()
                WHERE t.is_available = 1";

        if ($branch_id) {
            $sql .= " AND t.branch_id = :branch_id";
            $this->db->query($sql);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query($sql);
        }

        return $this->db->resultSet();
    }
}
