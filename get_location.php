<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

// Krijo lidhjen me databazën
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrollo lidhjen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, city, description FROM locations";
$result = $conn->query($sql);

$locations = array();
while($row = $result->fetch_assoc()) {
    $locations[] = $row;
}

echo json_encode($locations);

$conn->close();
?>
