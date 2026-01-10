<?php
class Reports extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
    }

    private function checkAdminAuth() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $reportModel = $this->model('Report_model');
        
        $data['judul'] = 'Revenue Reports - Bille Billiards';
        $data['daily_revenue'] = $reportModel->getDailyRevenue();
        $data['monthly_revenue'] = $reportModel->getMonthlyRevenue();
        $data['yearly_revenue'] = $reportModel->getYearlyRevenue();
        $data['top_products'] = $reportModel->getTopSellingProducts(5);
        
        $this->view('templates/header', $data);
        $this->view('admin/reports/index', $data);
        $this->view('templates/footer');
    }

    public function daily() {
        $reportModel = $this->model('Report_model');
        
        $date = $_GET['date'] ?? date('Y-m-d');
        $data['judul'] = 'Daily Revenue Report - Bille Billiards';
        $data['date'] = $date;
        $data['revenue_data'] = $reportModel->getRevenueByDate($date);
        $data['orders'] = $reportModel->getOrdersByDate($date);
        
        $this->view('templates/header', $data);
        $this->view('admin/reports/daily', $data);
        $this->view('templates/footer');
    }

    public function monthly() {
        $reportModel = $this->model('Report_model');
        
        $month = $_GET['month'] ?? date('Y-m');
        $data['judul'] = 'Monthly Revenue Report - Bille Billiards';
        $data['month'] = $month;
        $data['revenue_data'] = $reportModel->getRevenueByMonth($month);
        $data['daily_breakdown'] = $reportModel->getDailyRevenueForMonth($month);
        
        $this->view('templates/header', $data);
        $this->view('admin/reports/monthly', $data);
        $this->view('templates/footer');
    }

    public function yearly() {
        $reportModel = $this->model('Report_model');
        
        $year = $_GET['year'] ?? date('Y');
        $data['judul'] = 'Yearly Revenue Report - Bille Billiards';
        $data['year'] = $year;
        $data['revenue_data'] = $reportModel->getRevenueByYear($year);
        $data['monthly_breakdown'] = $reportModel->getMonthlyRevenueForYear($year);
        
        $this->view('templates/header', $data);
        $this->view('admin/reports/yearly', $data);
        $this->view('templates/footer');
    }
}