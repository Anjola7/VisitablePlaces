<?php
session_start();

// Kontrollo nëse përdoruesi është loguar dhe ka rol admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Krijo lidhjen me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lidhja me bazën e të dhënave dështoi: " . $conn->connect_error);
}

// Marrim të dhënat nga formulari
$location_id = $_POST['location_id'];
$location_name = $_POST['location_name'];
$location_city = $_POST['location_city'];
$location_photo = $_FILES['location_photo']['name'];
$location_photo_tmp = $_FILES['location_photo']['tmp_name'];

// Nëse ka një foto të re, ruajmë atë
if (!empty($location_photo)) {
    $upload_dir = 'uploads/';
    $upload_file = $upload_dir . basename($location_photo);
    
    if (move_uploaded_file($location_photo_tmp, $upload_file)) {
        $photo_query = ", location_photo='$upload_file'";
    } else {
        die("Gabim gjatë ngarkimit të fotos.");
    }
} else {
    $photo_query = "";
}

// Përditësojmë vendndodhjen
$sql = "UPDATE locations SET location_name=?, location_city=? $photo_query WHERE location_id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssi", $location_name, $location_city, $location_id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php"); // Kthehu në panelin e administratës
    } else {
        echo "Gabim gjatë përditësimit të vendndodhjes: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Gabim në përgatitjen e deklaratës SQL: " . $conn->error;
}

$conn->close();
?>
