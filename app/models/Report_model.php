<?php
class Report_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getDailyRevenue() {
        $this->db->query("
            SELECT DATE(created_at) as date, SUM(total_amount) as revenue 
            FROM orders 
            WHERE DATE(created_at) = CURDATE()
            GROUP BY DATE(created_at)
        ");
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getMonthlyRevenue() {
        $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as revenue 
            FROM orders 
            WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ");
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getYearlyRevenue() {
        $this->db->query("
            SELECT YEAR(created_at) as year, SUM(total_amount) as revenue 
            FROM orders 
            WHERE YEAR(created_at) = YEAR(CURDATE())
            GROUP BY YEAR(created_at)
        ");
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getRevenueByDate($date) {
        $this->db->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as average_order_value
            FROM orders 
            WHERE DATE(created_at) = :date
        ");
        $this->db->bind('date', $date);
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getOrdersByDate($date) {
        $this->db->query("
            SELECT o.*, u.name as customer_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE DATE(o.created_at) = :date
            ORDER BY o.created_at DESC
        ");
        $this->db->bind('date', $date);
        $this->db->execute();
        return $this->db->resultSet();
    }
    
    public function getRevenueByMonth($month) {
        $this->db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as average_order_value
            FROM orders 
            WHERE DATE_FORMAT(created_at, '%Y-%m') = :month
        ");
        $this->db->bind('month', $month);
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getDailyRevenueForMonth($month) {
        $this->db->query("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as total_orders,
                SUM(total_amount) as daily_revenue
            FROM orders 
            WHERE DATE_FORMAT(created_at, '%Y-%m') = :month
            GROUP BY DATE(created_at)
            ORDER BY DATE(created_at)
        ");
        $this->db->bind('month', $month);
        $this->db->execute();
        return $this->db->resultSet();
    }
    
    public function getRevenueByYear($year) {
        $this->db->query("
            SELECT 
                YEAR(created_at) as year,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as average_order_value
            FROM orders 
            WHERE YEAR(created_at) = :year
        ");
        $this->db->bind('year', $year);
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getMonthlyRevenueForYear($year) {
        $this->db->query("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                COUNT(*) as total_orders,
                SUM(total_amount) as monthly_revenue
            FROM orders 
            WHERE YEAR(created_at) = :year
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY DATE_FORMAT(created_at, '%Y-%m')
        ");
        $this->db->bind('year', $year);
        $this->db->execute();
        return $this->db->resultSet();
    }
    
    public function getTopSellingProducts($limit = 5) {
        $this->db->query("
            SELECT 
                p.name,
                COUNT(oi.product_id) as total_sold,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.subtotal) as total_revenue
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.status = 'paid'
            GROUP BY oi.product_id
            ORDER BY total_quantity DESC
            LIMIT :limit
        ");
        $this->db->bind('limit', $limit, PDO::PARAM_INT);
        $this->db->execute();
        return $this->db->resultSet();
    }
}