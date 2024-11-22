<?php
// Ky është vetëm një shembull i lidhjes me databazën
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lidhja me databazën dështoi: " . $conn->connect_error);
}

$sql = "SELECT name, city, photo, phone_number, type, contact_info FROM facilities";
$result = $conn->query($sql);
?>
