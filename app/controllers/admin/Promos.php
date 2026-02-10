<?php
class Promos extends Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminAuth();
    }

    private function checkAdminAuth() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'super_admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $promoModel = $this->model('Promo_model');

        $data['judul'] = 'Manajemen Promo - Bille Billiards';
        $data['promos'] = $promoModel->getAll();

        $this->view('admin/promos/index', $data);
    }

    public function create() {
        $data['judul'] = 'Tambah Promo Baru - Bille Billiards';

        $this->view('admin/promos/create', $data);
    }

    public function store() {
        $promoModel = $this->model('Promo_model');

        if(empty($_POST['code']) ||
        empty($_POST['discount_type']) ||
        empty($_POST['discount_value']) ||
        empty($_POST['start_date']) ||
        empty($_POST['end_date'])) {

            Flasher::setFlash('error', 'Data wajib belum lengkap');
            header('Location: ' . BASEURL . '/admin/promos/create');
            exit;
        }

        // Validasi percentage
        if($_POST['discount_type'] === 'percentage' && (float)$_POST['discount_value'] > 100){
            Flasher::setFlash('error', 'Diskon tidak boleh > 100%');
            header('Location: ' . BASEURL . '/admin/promos/create');
            exit;
        }

        // Validasi tanggal
        if($_POST['start_date'] > $_POST['end_date']){
            Flasher::setFlash('error', 'Tanggal mulai tidak boleh lebih besar');
            header('Location: ' . BASEURL . '/admin/promos/create');
            exit;
        }

        $data = [
            'branch_id' => !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : null,
            'code' => strtoupper(trim($_POST['code'])),
            'discount_type' => $_POST['discount_type'],
            'discount_value' => (float)$_POST['discount_value'],
            'min_purchase' => !empty($_POST['min_purchase']) ? (float)$_POST['min_purchase'] : 0,
            'max_discount' => !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : 0,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'usage_limit' => !empty($_POST['usage_limit']) ? (int)$_POST['usage_limit'] : 0,
            'used_count' => 0,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if(!$promoModel->create($data)){
            die('Insert promo gagal');
        }

        Flasher::setFlash('success', 'Promo berhasil ditambahkan');
        header('Location: ' . BASEURL . '/admin/promos');
    }


    public function edit($id) {
        $promoModel = $this->model('Promo_model');

        $data['judul'] = 'Edit Promo - Bille Billiards';
        $data['promo'] = $promoModel->getById($id);

        if(!$data['promo']) {
            Flasher::setFlash('error', 'Promo tidak ditemukan');
            header('Location: ' . BASEURL . '/admin/promos');
            exit;
        }

        $this->view('admin/promos/edit', $data);
    }

    public function update($id) {
        $promoModel = $this->model('Promo_model');

        if(empty($_POST['code']) ||
        empty($_POST['discount_type']) ||
        empty($_POST['discount_value']) ||
        empty($_POST['start_date']) ||
        empty($_POST['end_date'])) {

            Flasher::setFlash('error', 'Data wajib belum lengkap');
            header('Location: ' . BASEURL . '/admin/promos/edit/' . $id);
            exit;
        }

        // Validasi percentage
        if($_POST['discount_type'] === 'percentage' && (float)$_POST['discount_value'] > 100){
            Flasher::setFlash('error', 'Diskon tidak boleh > 100%');
            header('Location: ' . BASEURL . '/admin/promos/edit/' . $id);
            exit;
        }

        // Validasi tanggal
        if($_POST['start_date'] > $_POST['end_date']){
            Flasher::setFlash('error', 'Tanggal mulai tidak boleh lebih besar');
            header('Location: ' . BASEURL . '/admin/promos/edit/' . $id);
            exit;
        }

        $data = [
            'branch_id' => !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : null,
            'code' => strtoupper(trim($_POST['code'])),
            'discount_type' => $_POST['discount_type'],
            'discount_value' => (float)$_POST['discount_value'],
            'min_purchase' => !empty($_POST['min_purchase']) ? (float)$_POST['min_purchase'] : 0,
            'max_discount' => !empty($_POST['max_discount']) ? (float)$_POST['max_discount'] : 0,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'usage_limit' => !empty($_POST['usage_limit']) ? (int)$_POST['usage_limit'] : 0,
            'is_active' => isset($_POST['is_active']) && $_POST['is_active'] === 'on' ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if(!$promoModel->update($id, $data)){
            die('Update promo gagal');
        }

        Flasher::setFlash('success', 'Promo berhasil diperbarui');
        header('Location: ' . BASEURL . '/admin/promos');
    }

    public function destroy($id) {
        $promoModel = $this->model('Promo_model');

        if($promoModel->delete($id)) {
            Flasher::setFlash('success', 'Promo berhasil dihapus');
        } else {
            Flasher::setFlash('error', 'Gagal menghapus promo');
        }

        header('Location: ' . BASEURL . '/admin/promos');
        exit;
    }
}