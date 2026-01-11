<?php
class Reports extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
    }

    private function checkAdminAuth() {
        if(!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'super_admin' && $_SESSION['user_role'] !== 'branch_admin')) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $reportModel = $this->model('Report_model');

        $data['judul'] = 'Revenue Reports - Bille Billiards';

        // Get branch ID from session for branch admins, super admins can see all
        $branch_id = null;
        if ($_SESSION['user_role'] === 'branch_admin') {
            $branch_id = $_SESSION['branch_id'] ?? null;
        }

        $data['daily_revenue'] = $reportModel->getDailyRevenue($branch_id);
        $data['monthly_revenue'] = $reportModel->getMonthlyRevenue($branch_id);
        $data['yearly_revenue'] = $reportModel->getYearlyRevenue($branch_id);
        $data['top_products'] = $reportModel->getTopSellingProducts(5, $branch_id);

        $this->view('admin/reports/index', $data);
    }

    public function daily() {
        $reportModel = $this->model('Report_model');

        $date = $_GET['date'] ?? date('Y-m-d');
        $data['judul'] = 'Daily Revenue Report - Bille Billiards';
        $data['date'] = $date;

        // Get branch ID from session for branch admins, super admins can see all
        $branch_id = null;
        if ($_SESSION['user_role'] === 'branch_admin') {
            $branch_id = $_SESSION['branch_id'] ?? null;
        }

        $data['revenue_data'] = $reportModel->getRevenueByDate($date, $branch_id);
        $data['orders'] = $reportModel->getOrdersByDate($date, $branch_id);

        $this->view('admin/reports/daily', $data);
    }

    public function monthly() {
        $reportModel = $this->model('Report_model');

        $month = $_GET['month'] ?? date('Y-m');
        $data['judul'] = 'Monthly Revenue Report - Bille Billiards';
        $data['month'] = $month;

        // Get branch ID from session for branch admins, super admins can see all
        $branch_id = null;
        if ($_SESSION['user_role'] === 'branch_admin') {
            $branch_id = $_SESSION['branch_id'] ?? null;
        }

        $data['revenue_data'] = $reportModel->getRevenueByMonth($month, $branch_id);
        $data['daily_breakdown'] = $reportModel->getDailyRevenueForMonth($month, $branch_id);

        $this->view('admin/reports/monthly', $data);
    }

    public function yearly() {
        $reportModel = $this->model('Report_model');

        $year = $_GET['year'] ?? date('Y');
        $data['judul'] = 'Yearly Revenue Report - Bille Billiards';
        $data['year'] = $year;

        // Get branch ID from session for branch admins, super admins can see all
        $branch_id = null;
        if ($_SESSION['user_role'] === 'branch_admin') {
            $branch_id = $_SESSION['branch_id'] ?? null;
        }

        $data['revenue_data'] = $reportModel->getRevenueByYear($year, $branch_id);
        $data['monthly_breakdown'] = $reportModel->getMonthlyRevenueForYear($year, $branch_id);

        $this->view('admin/reports/yearly', $data);
    }
}