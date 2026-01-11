<?php
class Booking_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // CEK KETERSEDIAAN (LOGIKA INTI)
    public function checkAvailability($table_id, $date, $time, $duration) {
        $start_time = $date . ' ' . $time . ':00';
        $end_time = date('Y-m-d H:i:s', strtotime($start_time . " + $duration hours"));

        $query = "SELECT COUNT(*) as total FROM bookings
                  WHERE table_id = :table_id
                  AND payment_status != 'Cancelled'
                  AND (
                      (:start_time < end_time AND :end_time > start_time)
                  )";

        $this->db->query($query);
        $this->db->bind('table_id', $table_id);
        $this->db->bind('start_time', $start_time);
        $this->db->bind('end_time', $end_time);

        $result = $this->db->single();
        return ($result->total == 0); // True jika tersedia
    }

    public function createBooking($data) {
        $query = "INSERT INTO bookings (user_id, branch_id, table_id, booking_code, start_time, duration, end_time, total_price, payment_status)
                  VALUES (:user_id, :branch_id, :table_id, :booking_code, :start_time, :duration, :end_time, :total_price, 'Unpaid')";

        // Hitung end_time otomatis
        $end_time = date('Y-m-d H:i:s', strtotime($data['start_time'] . " + " . $data['duration'] . " hours"));

        $this->db->query($query);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('table_id', $data['table_id']);
        $this->db->bind('booking_code', $data['booking_code']);
        $this->db->bind('start_time', $data['start_time']);
        $this->db->bind('duration', $data['duration']);
        $this->db->bind('end_time', $end_time);
        $this->db->bind('total_price', $data['total_price']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getBookingByCode($code) {
        $this->db->query("SELECT * FROM bookings WHERE booking_code = :code");
        $this->db->bind('code', $code);
        return $this->db->single();
    }

    // Method untuk mendapatkan booking aktif
    public function getActiveBookings() {
        $this->db->query("SELECT * FROM bookings WHERE payment_status = 'Paid' AND end_time >= NOW()");
        return $this->db->resultSet();
    }

    public function getAll() {
        $this->db->query("SELECT * FROM bookings ORDER BY id ASC");
        return $this->db->resultSet();
    }

    // Method untuk mendapatkan total revenue
    public function getTotalRevenue() {
        $this->db->query("SELECT SUM(total_price) as total_revenue FROM bookings WHERE payment_status = 'Paid'");
        $result = $this->db->single();
        return $result->total_revenue ? $result->total_revenue : 0;
    }

    // Method untuk mendapatkan booking terbaru
    public function getRecentBookings($limit = 5) {
        $this->db->query("SELECT b.booking_code, u.name as customer_name, b.start_time, b.total_price, b.payment_status
                          FROM bookings b
                          JOIN users u ON b.user_id = u.id
                          ORDER BY b.created_at DESC
                          LIMIT :limit");
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }
}