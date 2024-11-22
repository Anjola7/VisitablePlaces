<?php
// deleteFacility.php
require_once 'db_connection.php'; // Shto lidhjen e bazës së të dhënave

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $facilityId = $data['id'];
    $stmt = $pdo->prepare("DELETE FROM facilities WHERE id = ?");
    $stmt->execute([$facilityId]);
    echo json_encode(['status' => 'success']);
}
?>
