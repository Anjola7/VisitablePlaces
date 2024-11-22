<?php
session_start(); // Aktivizo seancat

// Kontrollo nëse përdoruesi është loguar dhe ka rol admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Krijo lidhjen me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lidhja me bazën e të dhënave dështoi: " . $conn->connect_error);
}

// Kontrollo nëse është dhënë një ID për të fshirë
if (isset($_GET['id'])) {
    $location_id = intval($_GET['id']); // Sigurohu që ID është një numër

    // Krijo kërkesën SQL për të fshirë vendndodhjen
    $sql = "DELETE FROM locations WHERE location_id=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $location_id);

        if ($stmt->execute()) {
            echo "<p class='text-success'>Vendndodhja është fshirë me sukses!</p>";
        } else {
            echo "<p class='text-danger'>Gabim gjatë fshirjes së vendndodhjes: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='text-danger'>Gabim në përgatitjen e deklaratës SQL: " . $conn->error . "</p>";
    }
}

// Mbyll lidhjen me bazën e të dhënave
$conn->close();

// Ridrejto te faqja admin pas fshirjes
header("Location: admin_dashboard.php");
exit();
?>

