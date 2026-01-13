<?php

class App {
    protected $controller = 'Home'; // Controller default
    protected $method = 'index';     // Method default
    protected $params = [];          // Parameter default

    public function __construct() {
        $url = $this->parseURL();
        $subdir = null; // Initialize subdirectory variable

        // 1. SET CONTROLLER
        if (isset($url[0])) {
            // Check if it's a subdirectory controller (like admin/something)
            if (isset($url[1]) && file_exists('../app/controllers/' . $url[0] . '/' . $url[1] . '.php')) {
                // This is a subdirectory controller (e.g., /admin/bookings -> Bookings.php in admin directory)
                $this->controller = $url[1];
                $subdir = $url[0];
                unset($url[0], $url[1]);

                require_once '../app/controllers/' . $subdir . '/' . $this->controller . '.php';
                $this->controller = new $this->controller;
            } elseif (file_exists('../app/controllers/' . $url[0] . '.php')) {
                // Regular controller in main directory (e.g., /admin -> Admin.php in main directory)
                $this->controller = $url[0];
                unset($url[0]);

                require_once '../app/controllers/' . $this->controller . '.php';
                $this->controller = new $this->controller;
            } else {
                // If no controller found, keep the default
                $this->controller = 'Home';
            }
        }

        // 2. SET METHOD
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 3. SET PARAMS (Jika ada sisa URL)
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // 4. JALANKAN CONTROLLER & METHOD SERTA KIRIM PARAMS
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    // Fungsi untuk membersihkan dan memecah URL
    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}