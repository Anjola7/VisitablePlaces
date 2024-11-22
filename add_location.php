<?php
include 'db_connection.php';

$location_name = $_POST['location_name'];
$location_city = $_POST['location_city'];
$description = $_POST['description'];
$location_price = $_POST['location_price'];

$location_photo = $_FILES['location_photo']['name'];
$target = 'uploads/' . basename($location_photo);
move_uploaded_file($_FILES['location_photo']['tmp_name'], $target);

$sql = "INSERT INTO locations (location_name, location_city, description, location_price, location_photo) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $location_name, $location_city, $description, $location_price, $location_photo);
$stmt->execute();

echo "Location added successfully!";
$stmt->close();
$conn->close();
?>
