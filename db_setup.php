<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Skrip sementara untuk mencipta jadual pangkalan data di Railway
$host = 'switchback.proxy.rlwy.net';
$user = 'root';
$pass = 'TiVRpUhvuXVWKkQwrOOyEYougUgyltwY';
$db   = 'railway';
$port = 53261;

try {
    if (!extension_loaded('mysqli')) {
        die("<h1>Ekstensi MySQLi tidak ditemui!</h1><p>Sila pastikan persekitaran PHP anda menyokong MySQLi.</p>");
    }
    $conn = new mysqli($host, $user, $pass, $db, $port);
    echo "<h1>Sambungan Berjaya!</h1>";

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
        echo "<h2>Berjaya! Jadual ucapan_raya dicipta.</h2>";
        echo "<p>Anda boleh memadam fail db_setup.php ini sekarang.</p>";
        echo "<a href='index.php'>Pergi ke Laman Utama</a>";
    } else {
        echo "Ralat mencipta jadual: " . $conn->error;
    }
    $conn->close();
} catch (Exception $e) {
    echo "<h1>Ralat Berlaku!</h1>";
    echo "<p>Mesej: " . $e->getMessage() . "</p>";
    echo "<p>Kod: " . $e->getCode() . "</p>";
    echo "<hr><p>Pastikan butiran sambungan MySQL di bawah adalah betul berpandukan tab 'Connect' di Railway.</p>";
}
?>
