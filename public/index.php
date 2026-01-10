<?php
// Jalankan Session (Penting untuk login & keranjang belanja)
if( !session_id() ) session_start();

// Panggil file Konstanta (Config)
require_once '../app/core/Constants.php';

// Panggil file Core MVC secara manual (atau bisa pakai autoloader nanti)
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

// Inisialisasi App (Jalankan sistem routing)
$app = new App;