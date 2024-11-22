<?php
include 'database_connection.php'; // Përfshini lidhjen me bazën e të dhënave

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $city = $_POST['city'];
    $photo = $_POST['photo'];

    // Përgatitja e deklaratës për përditësimin e vendodhjes
    $sql = "UPDATE locations SET name = ?, city = ?, photo = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    // Kontrolloni nëse përgatitja është e suksesshme
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Përgatitja e deklaratës dështoi.']);
        exit();
    }

    // Ekzekutoni deklaratën
    if ($stmt->execute([$name, $city, $photo, $id])) {
        echo json_encode(['status' => 'success', 'message' => 'Vendodhja është përditësuar me sukses.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Dështoi përditësimi i vendodhjes.']);
    }

    $stmt->close();
    $conn->close();
}
?>
