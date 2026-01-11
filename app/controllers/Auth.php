<?php
class Auth extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['judul'] = 'Login - Bille Billiards';
        
        $this->view('templates/header', $data);
        $this->view('auth/login', $data);
        $this->view('templates/footer');
    }

    public function login() {
        $userModel = $this->model('User_model');
        
        // Validasi input
        if(empty($_POST['email']) || empty($_POST['password'])) {
            Flasher::setFlash('error', 'Email dan password harus diisi');
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Cek user di database
        $user = $userModel->getUserByEmail($email);
        
        if($user && password_verify($password, $user->password)) {
            // Set session
            session_start();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_role'] = $user->role;
            
            // Redirect berdasarkan role
            if($user->role == 'super_admin' || $user->role == 'branch_admin') {
                header('Location: ' . BASEURL . '/admin');
            } else {
                header('Location: ' . BASEURL . '/home');
            }
            exit;
        } else {
            Flasher::setFlash('error', 'Email atau password salah');
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ' . BASEURL . '/home');
        exit;
    }

    public function register() {
        $data['judul'] = 'Register - Bille Billiards';
        
        $this->view('templates/header', $data);
        $this->view('auth/register', $data);
        $this->view('templates/footer');
    }

    public function register_process() {
        $userModel = $this->model('User_model');
        
        // Validasi input
        if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['phone'])) {
            Flasher::setFlash('error', 'Semua field harus diisi');
            header('Location: ' . BASEURL . '/auth/register');
            exit;
        }
        
        // Validasi format email
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            Flasher::setFlash('error', 'Format email tidak valid');
            header('Location: ' . BASEURL . '/auth/register');
            exit;
        }
        
        // Validasi panjang password
        if(strlen($_POST['password']) < 6) {
            Flasher::setFlash('error', 'Password minimal 6 karakter');
            header('Location: ' . BASEURL . '/auth/register');
            exit;
        }
        
        // Cek apakah email sudah terdaftar
        if($userModel->getUserByEmail($_POST['email'])) {
            Flasher::setFlash('error', 'Email sudah terdaftar');
            header('Location: ' . BASEURL . '/auth/register');
            exit;
        }
        
        // Siapkan data untuk registrasi
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'phone' => $_POST['phone']
        ];
        
        // Registrasi user
        if($userModel->registerUser($data)) {
            Flasher::setFlash('success', 'Registrasi berhasil, silakan login');
            header('Location: ' . BASEURL . '/auth');
        } else {
            Flasher::setFlash('error', 'Registrasi gagal');
            header('Location: ' . BASEURL . '/auth/register');
        }
        exit;
    }
}