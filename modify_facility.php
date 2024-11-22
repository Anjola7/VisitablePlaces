<?php
// modify_facility.php

// Krijo lidhjen me bazën e të dhënave
$servername = "localhost"; // Ndrysho sipas konfigurimeve të tua
$username = "root";        // Ndrysho sipas konfigurimeve të tua
$password = "";            // Ndrysho sipas konfigurimeve të tua
$dbname = "travel_db"; // Ndrysho sipas konfigurimeve të tua

try {
    // Krijo një lidhje të re me PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Vendosimi i mënyrës së gabimeve
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Kontrollo nëse kërkesa është POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Merr të dhënat nga forma
        $id = $_POST['facilityId'];
        $name = $_POST['facilityName'];
        // Merr të dhënat e tjera sipas nevojës

        // Përgatiti kërkesën për përditësim
        $sql = "UPDATE facilities SET name = :name WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // Lidh parametrat dhe ekzekuto
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':id', $id);
        
        // Ekzekuto kërkesën
        $stmt->execute();
        
        // Kthe një përgjigje të suksesshme
        echo json_encode(['status' => 'success', 'message' => 'Facility updated successfully']);
    } else {
        // Nëse kërkesa nuk është POST
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
} catch (PDOException $e) {
    // Kthe një përgjigje të gabimit
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
