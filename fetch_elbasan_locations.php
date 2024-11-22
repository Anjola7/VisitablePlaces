<?php
// Krijo lidhjen me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrollo lidhjen
if ($conn->connect_error) {
    die("Lidhja e dështuar: " . $conn->connect_error);
}

// Kërkesa për të marrë vendndodhjet që janë në qytetin Gjirokastër
$sql = "SELECT location_name FROM locations WHERE location_city = 'Elbasan'";
$result = $conn->query($sql);

// Kontrollo nëse kërkesa është e suksesshme
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<li><a class="dropdown-item" href="#">' . htmlspecialchars($row['location_name']) . '</a></li>';
    }
} else {
    echo '<li><a class="dropdown-item" href="#">Nuk ka vendndodhje</a></li>';
}

$conn->close();
?>