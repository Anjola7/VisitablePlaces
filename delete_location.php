<?php
require 'db_connection.php'; // Include your DB connection

if (isset($_GET['id'])) {
    $location_id = $_GET['id'];

    // Delete location from the database
    $stmt = $mysqli->prepare("DELETE FROM locations WHERE location_id = ?");
    $stmt->bind_param("i", $location_id);
    $stmt->execute();
    $stmt->close();

}
?>
