<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $facility_id = intval($_GET['id']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "travel_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Lidhja me bazën e të dhënave dështoi: " . $conn->connect_error);
    }

    $sql = "DELETE FROM facilities WHERE id=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $facility_id);

        if ($stmt->execute()) {
            header("Location: admin_dashboard.php"); // Riinicializo faqen pas fshirjes
            exit();
        } else {
            echo "Gabim gjatë fshirjes: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Gabim në përgatitjen e deklaratës SQL: " . $conn->error;
    }

    $conn->close();
} else {
    echo "ID e facilitetit nuk është përcaktuar.";
}
?>
