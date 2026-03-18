<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fallback hardcode credentials if ENV is missing
$host = getenv('MYSQLHOST') ?: 'switchback.proxy.rlwy.net';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: 'TiVRpUhvuXVWKkQwrOOyEYougUgyltwY';
$db   = getenv('MYSQLDATABASE') ?: 'railway';
$port = getenv('MYSQLPORT') ?: 53261;

try {
    if (!extension_loaded('mysqli')) {
        die("Ekstensi MySQLi tidak aktif di server ini.");
    }
    $conn = new mysqli($host, $user, $pass, $db, $port);
} catch (Exception $e) {
    die("<h3>Sambungan Pangkalan Data Gagal!</h3> <p>Ralat: " . $e->getMessage() . "</p>");
}
?>
