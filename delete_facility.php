<?php
require 'db_connection.php'; // Include your DB connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete location from the database
    $stmt = $mysqli->prepare("DELETE FROM facilities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

}
?>

