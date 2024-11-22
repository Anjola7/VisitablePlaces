<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lidhja e dështuar: " . $conn->connect_error);
}

$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

$sql = "SELECT location_name, image_path, location_description 
        FROM locations 
        WHERE location_city = 'Tirana' AND location_name LIKE '%$query%'";

$result = $conn->query($sql);

$locations = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}

echo json_encode($locations);

$conn->close();
?>
