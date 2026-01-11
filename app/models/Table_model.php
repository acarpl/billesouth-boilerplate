<?php
class Table_model {
    private $table = 'tables';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAll() {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY id ASC");
        return $this->db->resultSet();
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
        $sql = "SELECT t.*, b.branch_name,
                       CASE
                           WHEN bkt.id IS NOT NULL THEN 'occupied'
                           ELSE t.status
                       END as table_status
                FROM " . $this->table . " t
                JOIN branches b ON t.branch_id = b.id
                LEFT JOIN bookings bkt ON t.id = bkt.table_id
                    AND bkt.payment_status = 'Paid'
                    AND bkt.start_time <= NOW()
                    AND bkt.end_time >= NOW()
                ";

        if ($branch_id) {
            $sql .= " WHERE t.branch_id = :branch_id";
            $this->db->query($sql);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query($sql);
        }

        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query("INSERT INTO " . $this->table . " (branch_id, table_number, type, price_per_hour, status) VALUES (:branch_id, :table_number, :type, :price_per_hour, :status)");
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('table_number', $data['table_number']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('price_per_hour', $data['price_per_hour']);
        $this->db->bind('status', $data['status']);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function update($id, $data) {
        $this->db->query("UPDATE " . $this->table . " SET branch_id = :branch_id, table_number = :table_number, type = :type, price_per_hour = :price_per_hour, status = :status WHERE id = :id");
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('table_number', $data['table_number']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('price_per_hour', $data['price_per_hour']);
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
}
