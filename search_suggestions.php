<?php
$host = 'localhost';
$dbname = 'travel_db';
$username = 'root';
$password = '';

$conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

$term = $_GET['term'];

// Ndërtoni pyetjen SQL për të marrë sugjerime
$query = $conn->prepare("SELECT location_name FROM locations WHERE location_name LIKE :term LIMIT 10");
$query->execute(['term' => "%$term%"]);
$suggestions = $query->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($suggestions);
?>
