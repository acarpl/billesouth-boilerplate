<?php
// Debug output untuk melihat apakah file ini diakses
echo "<!-- Debug: Public index.php loaded -->";

// Jalankan Session (Penting untuk login & keranjang belanja)
if( !session_id() ) session_start();

// Debug session info
echo "<!-- Debug: Session ID: " . session_id() . " -->";
echo "<!-- Debug: User Role: " . ($_SESSION['user_role'] ?? 'not set') . " -->";
echo "<!-- Debug: User ID: " . ($_SESSION['user_id'] ?? 'not set') . " -->";

// Panggil file Konstanta (Config)
require_once '../app/core/Constants.php';

// Debug constants
echo "<!-- Debug: Constants loaded, BASEURL: " . BASEURL . " -->";

// Panggil file Core MVC secara manual (atau bisa pakai autoloader nanti)
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
require_once '../app/core/Flasher.php';

// Inisialisasi App (Jalankan sistem routing)
echo "<!-- Debug: About to initialize App -->";
$app = new App;
echo "<!-- Debug: App initialized -->";