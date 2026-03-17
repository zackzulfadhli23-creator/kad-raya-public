<?php
// Skrip sementara untuk mencipta jadual pangkalan data di Railway
$host = 'switchback.proxy.rlwy.net';
$user = 'root';
$pass = 'TiVRpUhvuXVWKkQwrOOyEYougUgyltwY';
$db   = 'railway';
$port = 53261;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS ucapan_raya (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(10) NOT NULL UNIQUE,
    nama_pengirim VARCHAR(100) NOT NULL,
    nama_penerima VARCHAR(100) NOT NULL,
    mesej TEXT NOT NULL,
    tema_warna VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

if ($conn->query($sql) === TRUE) {
    echo "<h1>Berjaya! Jadual ucapan_raya dicipta.</h1>";
    echo "<p>Anda boleh memadam fail db_setup.php ini sekarang.</p>";
    echo "<a href='index.php'>Pergi ke Laman Utama</a>";
} else {
    echo "Ralat mencipta jadual: " . $conn->error;
}

$conn->close();
?>
