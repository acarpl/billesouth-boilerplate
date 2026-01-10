<?php

class Auth extends Controller {

    /**
     * Halaman Utama Auth (Menampilkan Login)
     * Akses: bille.id/auth
     */
    public function index() {
        // Jika user sudah login, arahkan ke home sesuai role agar tidak login ulang
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['user_role']);
        }

        $data['judul'] = 'Login - Bille Billiards';
        $this->view('auth/login', $data);
    }

    /**
     * Halaman Register
     * Akses: bille.id/auth/register
     */
    public function register() {
        $data['judul'] = 'Register - Bille Billiards';
        $this->view('auth/register', $data);
    }

    /**
     * Proses Login (Handling POST)
     */
    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->model('User_model')->getUserByEmail($email);

        if ($user) {
            // Verifikasi Password Hash
            if (password_verify($password, $user->password)) {
                
                // Set Session Data
                $_SESSION['user_id']   = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = $user->role;
                $_SESSION['branch_id'] = $user->branch_id; // Khusus admin cabang

                // Redirect berdasarkan role
                $this->redirectBasedOnRole($user->role);

            } else {
                // Password Salah
                $this->showAlert('Password yang Anda masukkan salah!', BASEURL . '/auth');
            }
        } else {
            // Email Tidak Terdaftar
            $this->showAlert('Email tidak ditemukan!', BASEURL . '/auth');
        }
    }

    /**
     * Proses Registrasi (Handling POST)
     */
    public function processRegister() {
        $name             = $_POST['name'];
        $email            = $_POST['email'];
        $phone            = $_POST['phone'];
        $password         = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // 1. Validasi Password Match
        if ($password !== $confirm_password) {
            $this->showAlert('Konfirmasi password tidak cocok!', BASEURL . '/auth/register');
            exit;
        }

        // 2. Cek apakah email sudah ada di database
        if ($this->model('User_model')->getUserByEmail($email)) {
            $this->showAlert('Email sudah terdaftar, silakan gunakan email lain.', BASEURL . '/auth/register');
            exit;
        }

        // 3. Siapkan data untuk dikirim ke model
        $data = [
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $password
        ];

        // 4. Eksekusi Register melalui Model
        if ($this->model('User_model')->registerUser($data) > 0) {
            $this->showAlert('Pendaftaran berhasil! Silakan login.', BASEURL . '/auth');
        } else {
            $this->showAlert('Terjadi kesalahan saat mendaftar, coba lagi nanti.', BASEURL . '/auth/register');
        }
    }

    /**
     * Proses Logout
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASEURL . '/auth');
        exit;
    }

    /**
     * Helper: Alert sederhana menggunakan JS
     */
    private function showAlert($message, $redirect) {
        echo "<script>
                alert('$message');
                window.location.href = '$redirect';
              </script>";
    }

    /**
     * Helper: Pengalihan halaman berdasarkan hak akses
     */
    private function redirectBasedOnRole($role) {
        if ($role == 'super_admin' || $role == 'branch_admin') {
            header('Location: ' . BASEURL . '/admin');
        } else {
            header('Location: ' . BASEURL . '/home');
        }
        exit;
    }
}