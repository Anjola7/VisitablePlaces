<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

// Krijo lidhjen
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Kontrollo lidhjen
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
