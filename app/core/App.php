<?php

class App {
    protected $controller = 'Home'; // Controller default
    protected $method = 'index';     // Method default
    protected $params = [];          // Parameter default

    public function __construct()
    {
        $url = $this->parseURL();

        // ================= CONTROLLER =================
        if (!empty($url)) {

            // CEK SUBFOLDER CONTROLLER
            if (
                isset($url[1]) &&
                is_dir('../app/controllers/' . $url[0]) &&
                file_exists('../app/controllers/' . $url[0] . '/' . ucfirst($url[1]) . '.php')
            ) {

                $subdir = $url[0];
                $this->controller = ucfirst($url[1]);

                require_once '../app/controllers/' . $subdir . '/' . $this->controller . '.php';
                $this->controller = new $this->controller;

                array_shift($url); // hapus folder
                array_shift($url); // hapus controller
            }

            // CEK CONTROLLER ROOT
            elseif (file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {

                $this->controller = ucfirst($url[0]);

                require_once '../app/controllers/' . $this->controller . '.php';
                $this->controller = new $this->controller;

                array_shift($url);
            }

            else {
                $this->loadDefaultController();
            }

        } else {
            $this->loadDefaultController();
        }

        // ================= METHOD =================
        if (!empty($url) && method_exists($this->controller, $url[0])) {
            $this->method = $url[0];
            array_shift($url);
        }

        // ================= PARAMS =================
        $this->params = $url ?? [];

        // DEBUG
        var_dump([
            'controller' => $this->controller,
            'method' => $this->method,
            'params' => $this->params
        ]);
        // die;

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function loadDefaultController() {
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
    }

    public function loadModel($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model;
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