<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pengirim = $conn->real_escape_string($_POST['pengirim']);
    $penerima = $conn->real_escape_string($_POST['penerima']);
    $mesej    = $conn->real_escape_string($_POST['mesej']);
    $tema     = $conn->real_escape_string($_POST['tema']);
    
    // Uruskan muat naik gambar
    $image_path = NULL;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Semak fail gambar (Max 5MB)
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check !== false && $_FILES["gambar"]["size"] <= 5000000) {
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if(in_array($file_extension, $allowed_types)) {
                if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                    $image_path = $conn->real_escape_string($target_file);
                }
            }
        }
    }
    
    // Jana Unique ID
    $unique_id = substr(md5(uniqid(rand(), true)), 0, 8);
    
    $stmt = $conn->prepare("INSERT INTO ucapan_raya (unique_id, nama_pengirim, nama_penerima, mesej, tema_warna, image_path) VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("<h3>Ralat Pangkalan Data (HTTP 500)</h3><p>Jadual 'ucapan_raya' belum wujud atau sambungan ke pangkalan data gagal.</p><p>Sila pastikan anda melawati <a href='db_setup.php'>Pautan Setup Pangkalan Data Pemasangan (db_setup.php)</a> terlebih dahulu sebelum menjana kad!</p> <br><small>Error detail: " . $conn->error . "</small>");
    }

    $stmt->bind_param("ssssss", $unique_id, $pengirim, $penerima, $mesej, $tema, $image_path);
    
    if ($stmt->execute()) {
        header("Location: view.php?id=" . $unique_id);
        exit();
    } else {
        echo "Ralat Pelaksanaan: " . $stmt->error;
    }
    
    $stmt->close();
}
$conn->close();
?>
