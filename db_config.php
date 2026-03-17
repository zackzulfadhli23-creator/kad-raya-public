<?php
// Fallback hardcode credentials if ENV is missing (untuk memanjangkan hayat demo)
$host = getenv('MYSQLHOST') ?: 'switchback.proxy.rlwy.net';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: 'TiVRpUhvuXVWKkQwrOOyEYougUgyltwY';
$db   = getenv('MYSQLDATABASE') ?: 'railway';
$port = getenv('MYSQLPORT') ?: 53261;

mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("<h3>Sambungan Pangkalan Data Gagal!</h3> <p>Sistem tidak dapat berhubung dengan Pangkalan Data. Sila pastikan langkah-langkah MySQL telah lengkap.</p> <p>Ralat: " . $conn->connect_error . "</p>");
}
?>
