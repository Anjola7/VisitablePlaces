<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

// Krijo lidhjen
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrollo lidhjen
if ($conn->connect_error) {
    die("Lidhja ka dështuar: " . $conn->connect_error);
}

// Query për të marrë të dhënat nga tabela locations për Tiranën
$sql = "SELECT location_id, location_name, location_city, location_photo, description FROM locations WHERE location_city = 'Tirane'";
$result = $conn->query($sql);

$locations = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Sigurohu që emri i fotografisë është i saktë dhe është në formatin e duhur
        $row['image_path'] = $row['location_photo'];
        // Shto përshkrimin në array
        $locations[] = $row;
    }
} 

$conn->close();

// Kthe të dhënat si JSON
echo json_encode($locations);
?>
