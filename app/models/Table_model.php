<?php
class Table_model {
    private $table = 'tables';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getActiveTables($branch_id = null)
    {
        $query = "SELECT t.*, b.branch_name, IFNULL(o.status, 'available') as table_status
                 FROM tables t
                 LEFT JOIN branches b ON b.id = t.branch_id
                 LEFT JOIN (SELECT table_id, status FROM orders WHERE status IN ('pending', 'ready', 'paid')) o ON o.table_id = t.id";

        if ($branch_id) {
            $query .= " WHERE t.branch_id = :branch_id";
            $this->db->query($query);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query($query);
        }

        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getTotalTables()
    {
        $this->db->query("SELECT COUNT(*) as total FROM " . $this->table);
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }

    public function getAll()
    {
        $this->db->query("SELECT t.*, b.branch_name FROM " . $this->table . " t LEFT JOIN branches b ON b.id = t.branch_id ORDER BY t.table_number ASC");
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function create($data)
    {
        $this->db->query("INSERT INTO " . $this->table . " (table_number, branch_id, capacity, status, type, created_at) VALUES (:table_number, :branch_id, :capacity, :status, :type, :created_at)");
        $this->db->bind('table_number', $data['table_number']);
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('capacity', $data['capacity']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('created_at', $data['created_at']);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function update($id, $data)
    {
        $this->db->query("UPDATE " . $this->table . " SET table_number = :table_number, branch_id = :branch_id, capacity = :capacity, status = :status, type = :type WHERE id = :id");
        $this->db->bind('table_number', $data['table_number']);
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('capacity', $data['capacity']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('type', $data['type']);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id = :id");
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}
