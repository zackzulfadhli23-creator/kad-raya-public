<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pengirim = $_POST['pengirim'];
    $penerima = $_POST['penerima'];
    $mesej    = $_POST['mesej'];
    $tema     = $_POST['tema'];
    
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
    
    try {
        // Sanitize input
        $s_pengirim = htmlspecialchars(strip_tags($pengirim));
        $s_penerima = htmlspecialchars(strip_tags($penerima));
        $s_mesej = $mesej; // Handled in view.php
        $s_tema = htmlspecialchars(strip_tags($tema));

        $stmt = $conn->prepare("INSERT INTO ucapan_raya (unique_id, nama_pengirim, nama_penerima, mesej, tema_warna, image_path) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $unique_id, $s_pengirim, $s_penerima, $s_mesej, $s_tema, $image_path);
        
        if ($stmt->execute()) {
            header("Location: view.php?id=" . $unique_id);
            exit();
        } else {
            throw new Exception("Ralat Pelaksanaan: " . $stmt->error);
        }
    } catch (Exception $e) {
         die("<h3>Ralat Pangkalan Data</h3><p>Gagal menyimpan ucapan.</p> <br><small>Error detail: " . $e->getMessage() . "</small>");
    } finally {
        if (isset($stmt)) $stmt->close();
    }
}
$conn->close();
?>
