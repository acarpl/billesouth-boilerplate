<?php

class Controller {
    public function __construct() {
        // Start session if not already started
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Fungsi untuk memanggil View (Tampilan)
    // $view = nama file di folder views, $data = data yang dikirim ke HTML
    public function view($view, $data = []) {
        require_once '../app/views/' . $view . '.php';
    }

    // Fungsi untuk memanggil Model (Data)
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model; // Mengembalikan object model baru
    }
}