<?php
include 'db_connection.php'; // Përfshini lidhjen me bazën e të dhënave

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Përgatitja e deklaratës për fshirjen e vendodhjes
    $sql = "DELETE FROM locations WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Kontrolloni nëse përgatitja është e suksesshme
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Përgatitja e deklaratës dështoi.']);
        exit();
    }

    // Ekzekutoni deklaratën
    if ($stmt->execute([$id])) {
        echo json_encode(['status' => 'success', 'message' => 'Vendodhja është fshirë me sukses.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dështoi fshirja e vendodhjes.']);
    }

    $stmt->close();
    $conn->close();
}
?>
