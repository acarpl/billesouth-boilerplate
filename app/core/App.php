<?php

class App {
    protected $controller = 'Home'; // Controller default
    protected $method = 'index';     // Method default
    protected $params = [];          // Parameter default

    public function __construct()
    {
        $url = $this->parseURL();
        // var_dump($url); // Debug: lihat hasil parse URL
        $subdir = null;

        // 1. SET CONTROLLER
        if (isset($url[0])) {
            if (isset($url[1]) && file_exists('../app/controllers/' . $url[0] . '/' . $url[1] . '.php')) {
                $this->controller = $url[1];
                $subdir = $url[0];
                unset($url[0], $url[1]);
                $url = array_values($url); // <--- PENTING

                require_once '../app/controllers/' . $subdir . '/' . $this->controller . '.php';
                $this->controller = new $this->controller;
            } elseif (file_exists('../app/controllers/' . $url[0] . '.php')) {
                $this->controller = $url[0];
                unset($url[0]);
                $url = array_values($url); // <--- PENTING

                require_once '../app/controllers/' . $this->controller . '.php';
                $this->controller = new $this->controller;
            }
        } else {
            // Load default controller
            require_once '../app/controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;
        }

        // 2. SET METHOD
        if (isset($url[0]) && method_exists($this->controller, $url[0])) {
            $this->method = $url[0];
            unset($url[0]);
        }

        // ===== PARAMS =====
        $this->params = $url ? array_values($url) : [];

        // 🔍 DEBUG DISINI
        // var_dump([
        //     'controller' => $this->controller,
        //     'method' => $this->method,
        //     'params' => $this->params
        // ]);
        // die;

        // ===== JALANKAN =====
        if (is_object($this->controller) && method_exists($this->controller, $this->method)) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } else {
            // Handle error: method does not exist
            http_response_code(404);
            echo "Error 404: Method not found.";
            exit;
        }
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