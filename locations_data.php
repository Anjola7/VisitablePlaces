<?php
header('Content-Type: application/json');

// Krijo lidhjen me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lidhja me bazën e të dhënave dështoi: " . $conn->connect_error);
}

// Merr të dhënat e vendndodhjeve për qytetin e menaxherit të loguar
$manager_city = $_SESSION['user_city'];
$sql = "SELECT * FROM locations WHERE location_city = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $manager_city);
$stmt->execute();
$result = $stmt->get_result();

$locations = [];
while ($row = $result->fetch_assoc()) {
    $locations[] = $row;
}

echo json_encode($locations);

$stmt->close();
$conn->close();
?>
