<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lidhja me databazën ka dështuar: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Merr të dhënat nga formulari
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $type = $_POST['type'];
    $contact_info = $_POST['contact_info'];
    $city = $_POST['city'];

    // Kontrollo nëse është ngarkuar një foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $photo = basename($_FILES['photo']['name']);
        } else {
            $photo = '';
        }
    } else {
        $photo = '';
    }

    // Përgatit dhe ekzekuto pyetjen SQL për të futur të dhënat
    $sql = "INSERT INTO facilities (name, phone_number, type, contact_info, city, photo) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $phone_number, $type, $contact_info, $city, $photo);

    if ($stmt->execute()) {
        echo "Facility është regjistruar me sukses!";
    } else {
        echo "Gabim: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
