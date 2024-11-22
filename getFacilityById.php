<?php
// getFacilityById.php
require_once 'db_connection.php'; // Shto lidhjen e bazës së të dhënave

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM facilities WHERE id = ?");
$stmt->execute([$id]);
$facility = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($facility);
?>
