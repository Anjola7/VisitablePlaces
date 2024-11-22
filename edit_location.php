<?php
require 'db_connection.php'; // Përfshini lidhjen tuaj me DB

if (isset($_POST['edit_location'])) {
    $location_id = $_POST['location_id'];
    $location_name = $_POST['location_name'];
    $location_city = $_POST['location_city'];
    $location_price = $_POST['location_price'];
    $description = $_POST['location_description'];
    $location_photo = $_FILES['location_photo']['name'];

    // Procesi i ngarkimit të fotos
    if ($location_photo) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($location_photo);
        if (!move_uploaded_file($_FILES['location_photo']['tmp_name'], $target_file)) {
            die('Failed to upload photo.');
        }
    } else {
        // Nëse nuk ka foto të re, përdorni foto ekzistuese
        $location_photo = $_POST['existing_photo'];
    }

    // Përgatitja e deklaratës SQL
    $stmt = $mysqli->prepare("UPDATE locations SET location_name = ?, location_city = ?, location_price = ?, description = ?, location_photo = ? WHERE location_id = ?");
    
    // Kontrolloni nëse deklarata është përgatitur me sukses
    if ($stmt === false) {
        die('Prepare failed: ' . $mysqli->error);
    }

    // Lidheni parametrat
    $stmt->bind_param("sssisi", $location_name, $location_city, $location_price, $description, $location_photo, $location_id);
    
    // Ekzekutoni deklaratën
    if (!$stmt->execute()) {
        die('Execute failed: ' . $stmt->error);
    }

    // Mbyllni deklaratën
    $stmt->close();
    
    // Redirect ose mesazh suksesi
    
    exit();
}
?>
