<?php

class Booking_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getActiveBookings($branch_id = null) {
    if ($branch_id === null) {
        $branch_id = 1; 
    }

    $query = "SELECT table_id FROM bookings 
              WHERE branch_id = :branch_id 
              AND payment_status != 'Cancelled' 
              AND NOW() BETWEEN start_time AND end_time";
    
    $this->db->query($query);
    $this->db->bind('branch_id', $branch_id);
    return $this->db->resultSet();
    }

    public function getTotalRevenue($branch_id = null) {
    // Kita hanya menghitung yang statusnya 'Paid' atau 'Finished'
    $query = "SELECT SUM(total_price) as total FROM bookings WHERE payment_status = 'Paid'";
    
    if ($branch_id) {
        $query .= " AND branch_id = :branch_id";
    }

    $this->db->query($query);
    
    if ($branch_id) {
        $this->db->bind('branch_id', $branch_id);
    }

    $result = $this->db->single();
    
    // Jika hasilnya NULL (belum ada transaksi), kembalikan 0
    return $result->total ? $result->total : 0;
    }

    public function getTotalBookings($branch_id = null) {
    $query = "SELECT COUNT(*) as total FROM bookings";
    
    if ($branch_id) {
        $query .= " WHERE branch_id = :branch_id";
    }

    $this->db->query($query);
    
    if ($branch_id) {
        $this->db->bind('branch_id', $branch_id);
    }

    $result = $this->db->single();
    return $result->total;
    }

    public function getRecentBookings($branch_id = null, $limit = 5) {
    // Kita Join dengan tabel users dan tables agar datanya lengkap
    $query = "SELECT bookings.*, users.name as user_name, tables.table_number 
              FROM bookings 
              JOIN users ON bookings.user_id = users.id 
              JOIN tables ON bookings.table_id = tables.id";
    
    if ($branch_id) {
        $query .= " WHERE bookings.branch_id = :branch_id";
    }

    $query .= " ORDER BY bookings.created_at DESC LIMIT :limit";

    $this->db->query($query);
    
    if ($branch_id) {
        $this->db->bind('branch_id', $branch_id);
    }
    
    $this->db->bind('limit', $limit);

    return $this->db->resultSet();
    }

    // 2. Cek ketersediaan sebelum checkout (Anti Tabrakan)
    public function checkAvailability($table_id, $date, $start_time, $duration) {
        $start = $date . ' ' . $start_time . ':00';
        $end = date('Y-m-d H:i:s', strtotime($start . " + $duration hours"));

        $query = "SELECT COUNT(*) as total FROM bookings 
                  WHERE table_id = :table_id 
                  AND payment_status != 'Cancelled'
                  AND (
                      (start_time < :end AND end_time > :start)
                  )";
        
        $this->db->query($query);
        $this->db->bind('table_id', $table_id);
        $this->db->bind('start', $start);
        $this->db->bind('end', $end);
        
        $result = $this->db->single();
        return ($result->total == 0);
    }

    // 3. Simpan Booking (FIX ERROR SUBTOTAL)
    public function createBooking($data) {
        $start = $data['start_time'];
        $end = date('Y-m-d H:i:s', strtotime($start . " + " . $data['duration'] . " hours"));

        $query = "INSERT INTO bookings (user_id, branch_id, table_id, booking_code, start_time, duration, end_time, subtotal, total_price, payment_status) 
                  VALUES (:user_id, :branch_id, :table_id, :booking_code, :start_time, :duration, :end_time, :subtotal, :total_price, 'Unpaid')";
        
        $this->db->query($query);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('branch_id', $data['branch_id']);
        $this->db->bind('table_id', $data['table_id']);
        $this->db->bind('booking_code', $data['booking_code']);
        $this->db->bind('start_time', $start);
        $this->db->bind('duration', $data['duration']);
        $this->db->bind('end_time', $end);
        $this->db->bind('subtotal', $data['subtotal']); // Data subtotal masuk sini
        $this->db->bind('total_price', $data['total_price']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    // 4. Ambil Detail untuk Halaman Pembayaran
    public function getBookingByCode($code) {
        $query = "SELECT bookings.*, tables.table_number, branches.branch_name, branches.phone_wa 
                  FROM bookings 
                  JOIN tables ON bookings.table_id = tables.id 
                  JOIN branches ON bookings.branch_id = branches.id 
                  WHERE bookings.booking_code = :code";
        $this->db->query($query);
        $this->db->bind('code', $code);
        return $this->db->single();
    }

    public function getAll($branch_id = null) {
    // Kita gunakan JOIN agar mendapatkan Nama User dan Nomor Meja
    $query = "SELECT bookings.*, users.name as user_name, users.phone, tables.table_number, branches.branch_name
              FROM bookings
              JOIN users ON bookings.user_id = users.id
              JOIN tables ON bookings.table_id = tables.id
              JOIN branches ON bookings.branch_id = branches.id";

    // Jika ada branch_id, filter berdasarkan cabang tersebut
    if ($branch_id) {
        $query .= " WHERE bookings.branch_id = :branch_id";
    }

    // Urutkan dari yang paling baru
    $query .= " ORDER BY bookings.created_at DESC";

    $this->db->query($query);

    if ($branch_id) {
        $this->db->bind('branch_id', $branch_id);
    }

    return $this->db->resultSet();
    }

    public function updateStatusByCode($code, $status) {
    $query = "UPDATE bookings SET payment_status = :status WHERE booking_code = :code";
    $this->db->query($query);
    $this->db->bind('status', $status);
    $this->db->bind('code', $code);
    $this->db->execute();
    return $this->db->rowCount();
    }

    public function getTodayBookings($branch_id = null) {
        $query = "SELECT * FROM bookings WHERE DATE(created_at) = CURDATE()";

        if ($branch_id) {
            $query .= " AND branch_id = :branch_id";
        }

        $this->db->query($query);

        if ($branch_id) {
            $this->db->bind('branch_id', $branch_id);
        }

        return $this->db->resultSet();
    }
}