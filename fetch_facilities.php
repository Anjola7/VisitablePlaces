<?php
// Krijo lidhjen me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

// Krijo lidhjen me bazën e të dhënave
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrollo lidhjen
if ($conn->connect_error) {
    die("Lidhja e dështuar: " . $conn->connect_error);
}

// Kërkesa për të marrë facilitetet që janë në qytetin Tirana
$sql = "SELECT name FROM facilities WHERE city = 'Tirana'";
$result = $conn->query($sql);

// Kontrollo nëse kërkesa është e suksesshme
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<li><a class="dropdown-item" href="#">' . htmlspecialchars($row['name']) . '</a></li>';
    }
} else {
    echo '<li><a class="dropdown-item" href="#">Nuk ka facilitete</a></li>';
}

$conn->close();
?>
