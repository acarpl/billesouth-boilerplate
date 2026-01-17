<?php
class Booking_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTodayBookings($branch_id) {
    $today = date('Y-m-d');
    
    // Query untuk mengambil booking yang tanggal start_time-nya adalah hari ini
    $query = "SELECT * FROM bookings 
              WHERE branch_id = :branch_id 
              AND DATE(start_time) = :today 
              AND payment_status != 'Cancelled'";
              
    $this->db->query($query);
    $this->db->bind('branch_id', $branch_id);
    $this->db->bind('today', $today);
    
    return $this->db->resultSet();
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

    public function createWalkInBooking($data) {
        $query = "INSERT INTO bookings (user_id, branch_id, table_id, booking_code, start_time, duration, end_time, total_price, customer_name, customer_phone, payment_status)
                  VALUES (:user_id, :branch_id, :table_id, :booking_code, :start_time, :duration, :end_time, :total_price, :customer_name, :customer_phone, :payment_status)";

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
        $this->db->bind('customer_name', $data['customer_name']);
        $this->db->bind('customer_phone', $data['customer_phone']);
        $this->db->bind('payment_status', $data['payment_status'] ?? 'Unpaid');

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updatePaymentStatus($booking_code, $payment_status, $payment_method) {
        $this->db->query("UPDATE bookings SET payment_status = :payment_status, payment_method = :payment_method WHERE booking_code = :booking_code");
        $this->db->bind('payment_status', $payment_status);
        $this->db->bind('payment_method', $payment_method);
        $this->db->bind('booking_code', $booking_code);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function getTotalBookings($branch_id = null) {
        if ($branch_id) {
            $this->db->query("SELECT COUNT(*) as total FROM bookings WHERE branch_id = :branch_id");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("SELECT COUNT(*) as total FROM bookings");
        }
        $result = $this->db->single();
        return $result->total;
    }

    public function getBookingByCode($code) {
        $this->db->query("SELECT * FROM bookings WHERE booking_code = :code");
        $this->db->bind('code', $code);
        return $this->db->single();
    }

    // Method untuk mendapatkan booking aktif
    public function getActiveBookings($branch_id = null) {
        if ($branch_id) {
            $this->db->query("SELECT * FROM bookings WHERE branch_id = :branch_id AND payment_status = 'Paid' AND end_time >= NOW()");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("SELECT * FROM bookings WHERE payment_status = 'Paid' AND end_time >= NOW()");
        }
        return $this->db->resultSet();
    }

    public function getAll($branch_id = null) {
        if ($branch_id) {
            $this->db->query("SELECT b.*, COALESCE(u.name, '') as customer_name, t.table_number, b.payment_status as status
                              FROM bookings b
                              LEFT JOIN users u ON b.user_id = u.id
                              LEFT JOIN tables t ON b.table_id = t.id
                              WHERE b.branch_id = :branch_id
                              ORDER BY b.id ASC");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("SELECT b.*, COALESCE(u.name, '') as customer_name, t.table_number, b.payment_status as status
                              FROM bookings b
                              LEFT JOIN users u ON b.user_id = u.id
                              LEFT JOIN tables t ON b.table_id = t.id
                              ORDER BY b.id ASC");
        }
        return $this->db->resultSet();
    }

    // Method untuk mendapatkan total revenue
    public function getTotalRevenue() {
        $this->db->query("SELECT SUM(total_price) as total_revenue FROM bookings WHERE payment_status = 'Paid'");
        $result = $this->db->single();
        return $result->total_revenue ? $result->total_revenue : 0;
    }

    // Method untuk mendapatkan booking terbaru
    public function getRecentBookings($limit = 5, $branch_id = null) {
        if ($branch_id) {
            $this->db->query("SELECT b.booking_code, u.name as customer_name, b.start_time, b.total_price, b.payment_status
                              FROM bookings b
                              JOIN users u ON b.user_id = u.id
                              WHERE b.branch_id = :branch_id
                              ORDER BY b.created_at DESC
                              LIMIT :limit");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("SELECT b.booking_code, u.name as customer_name, b.start_time, b.total_price, b.payment_status
                              FROM bookings b
                              JOIN users u ON b.user_id = u.id
                              ORDER BY b.created_at DESC
                              LIMIT :limit");
        }
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }
}