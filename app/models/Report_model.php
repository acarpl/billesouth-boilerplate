<?php
class Report_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    
    public function getDailyRevenue($branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT DATE(created_at) as date, SUM(grand_total) as revenue
                FROM orders
                WHERE DATE(created_at) = CURDATE() AND branch_id = :branch_id
                GROUP BY DATE(created_at)
            ");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT DATE(created_at) as date, SUM(grand_total) as revenue
                FROM orders
                WHERE DATE(created_at) = CURDATE()
                GROUP BY DATE(created_at)
            ");
        }
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getMonthlyRevenue($branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(grand_total) as revenue
                FROM orders
                WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') AND branch_id = :branch_id
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(grand_total) as revenue
                FROM orders
                WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ");
        }
        $this->db->execute();
        return $this->db->single();
    }

    public function getYearlyRevenue($branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT YEAR(created_at) as year, SUM(grand_total) as revenue
                FROM orders
                WHERE YEAR(created_at) = YEAR(CURDATE()) AND branch_id = :branch_id
                GROUP BY YEAR(created_at)
            ");
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT YEAR(created_at) as year, SUM(grand_total) as revenue
                FROM orders
                WHERE YEAR(created_at) = YEAR(CURDATE())
                GROUP BY YEAR(created_at)
            ");
        }
        $this->db->execute();
        return $this->db->single();
    }

    public function getRevenueByDate($date, $branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as total_revenue,
                    AVG(grand_total) as average_order_value
                FROM orders
                WHERE DATE(created_at) = :date AND branch_id = :branch_id
                GROUP BY DATE(created_at)
            ");
            $this->db->bind('date', $date);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as total_revenue,
                    AVG(grand_total) as average_order_value
                FROM orders
                WHERE DATE(created_at) = :date
                GROUP BY DATE(created_at)
            ");
            $this->db->bind('date', $date);
        }
        $this->db->execute();
        return $this->db->single();
    }
    
    public function getOrdersByDate($date, $branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT o.*, u.name as customer_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE DATE(o.created_at) = :date AND o.branch_id = :branch_id
                ORDER BY o.created_at DESC
            ");
            $this->db->bind('date', $date);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT o.*, u.name as customer_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE DATE(o.created_at) = :date
                ORDER BY o.created_at DESC
            ");
            $this->db->bind('date', $date);
        }
        $this->db->execute();
        return $this->db->resultSet();
    }
    
    public function getRevenueByMonth($month, $branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as total_revenue,
                    AVG(grand_total) as average_order_value
                FROM orders
                WHERE DATE_FORMAT(created_at, '%Y-%m') = :month AND branch_id = :branch_id
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ");
            $this->db->bind('month', $month);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as total_revenue,
                    AVG(grand_total) as average_order_value
                FROM orders
                WHERE DATE_FORMAT(created_at, '%Y-%m') = :month
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ");
            $this->db->bind('month', $month);
        }
        $this->db->execute();
        return $this->db->single();
    }

    public function getDailyRevenueForMonth($month, $branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as daily_revenue
                FROM orders
                WHERE DATE_FORMAT(created_at, '%Y-%m') = :month AND branch_id = :branch_id
                GROUP BY DATE(created_at)
                ORDER BY DATE(created_at)
            ");
            $this->db->bind('month', $month);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT
                    DATE(created_at) as date,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as daily_revenue
                FROM orders
                WHERE DATE_FORMAT(created_at, '%Y-%m') = :month
                GROUP BY DATE(created_at)
                ORDER BY DATE(created_at)
            ");
            $this->db->bind('month', $month);
        }
        $this->db->execute();
        return $this->db->resultSet();
    }

    public function getRevenueByYear($year, $branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT
                    YEAR(created_at) as year,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as total_revenue,
                    AVG(grand_total) as average_order_value
                FROM orders
                WHERE YEAR(created_at) = :year AND branch_id = :branch_id
                GROUP BY YEAR(created_at)
            ");
            $this->db->bind('year', $year);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT
                    YEAR(created_at) as year,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as total_revenue,
                    AVG(grand_total) as average_order_value
                FROM orders
                WHERE YEAR(created_at) = :year
                GROUP BY YEAR(created_at)
            ");
            $this->db->bind('year', $year);
        }
        $this->db->execute();
        return $this->db->single();
    }

    public function getMonthlyRevenueForYear($year, $branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as monthly_revenue
                FROM orders
                WHERE YEAR(created_at) = :year AND branch_id = :branch_id
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY DATE_FORMAT(created_at, '%Y-%m')
            ");
            $this->db->bind('year', $year);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as total_orders,
                    SUM(grand_total) as monthly_revenue
                FROM orders
                WHERE YEAR(created_at) = :year
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY DATE_FORMAT(created_at, '%Y-%m')
            ");
            $this->db->bind('year', $year);
        }
        $this->db->execute();
        return $this->db->resultSet();
    }
    
    public function getTopSellingProducts($limit = 5, $branch_id = null) {
        if ($branch_id) {
            $this->db->query("
                SELECT
                    p.name,
                    COUNT(oi.product_id) as total_sold,
                    SUM(oi.quantity) as total_quantity,
                    SUM(p.price * oi.quantity) as total_revenue
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.status = 'paid' AND o.branch_id = :branch_id
                GROUP BY oi.product_id
                ORDER BY total_quantity DESC
                LIMIT :limit
            ");
            $this->db->bind('limit', $limit, PDO::PARAM_INT);
            $this->db->bind('branch_id', $branch_id);
        } else {
            $this->db->query("
                SELECT
                    p.name,
                    COUNT(oi.product_id) as total_sold,
                    SUM(oi.quantity) as total_quantity,
                    SUM(p.price * oi.quantity) as total_revenue
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.status = 'paid'
                GROUP BY oi.product_id
                ORDER BY total_quantity DESC
                LIMIT :limit
            ");
            $this->db->bind('limit', $limit, PDO::PARAM_INT);
        }
        $this->db->execute();
        return $this->db->resultSet();
    }
}