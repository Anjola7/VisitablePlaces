<?php
// Përfshi skedarin për lidhjen me bazën e të dhënave
include 'db_connection.php';

// Marrim qytetin e menaxherit nga sesioni
session_start();
if (!isset($_SESSION['user_city'])) {
    die("Qyteti i menaxherit nuk është i vendosur në sesion.");
}

$manager_city = $_SESSION['user_city'];

// Debugging: Kontrollo se qyteti i marrë është i saktë
echo "Qyteti i Menaxherit: " . htmlspecialchars($manager_city) . "<br>";

// Marrim të dhënat e vendndodhjeve
$sql_locations = "SELECT * FROM locations WHERE location_city = ?";
$stmt_locations = $conn->prepare($sql_locations);
$stmt_locations->bind_param("s", $manager_city);
$stmt_locations->execute();
$result_locations = $stmt_locations->get_result();

// Debugging: Shfaq numrin e rezultateve dhe query-n
echo "Query për vendndodhjet: " . $sql_locations . " me qytet: " . htmlspecialchars($manager_city) . "<br>";
echo "Numri i rezultateve për vendndodhjet: " . $result_locations->num_rows . "<br>";

if ($result_locations === false) {
    die("Gabim në ekzekutimin e query-së për vendndodhjet: " . $conn->error);
}

// Marrim të dhënat e faciliteteve
$sql_facilities = "SELECT * FROM facilities WHERE city = ?";
$stmt_facilities = $conn->prepare($sql_facilities);
$stmt_facilities->bind_param("s", $manager_city);
$stmt_facilities->execute();
$result_facilities = $stmt_facilities->get_result();

// Debugging: Shfaq numrin e rezultateve dhe query-n
echo "Query për facilitete: " . $sql_facilities . " me qytet: " . htmlspecialchars($manager_city) . "<br>";
echo "Numri i rezultateve për facilitete: " . $result_facilities->num_rows . "<br>";

if ($result_facilities === false) {
    die("Gabim në ekzekutimin e query-së për facilitete: " . $conn->error);
}

// Shfaq të dhënat e vendndodhjeve
if ($result_locations->num_rows > 0) {
    echo "<h2>Vendndodhjet:</h2>";
    while ($row = $result_locations->fetch_assoc()) {
        echo "Emri: " . htmlspecialchars($row['location_name']) . "<br>";
        echo "Përshkrimi: " . htmlspecialchars($row['description']) . "<br>";
        echo "Çmimi: " . htmlspecialchars($row['location_price']) . "<br>";
        echo "Foto: <img src='" . htmlspecialchars($row['location_photo']) . "' alt='Location Photo' width='100'><br><br>";
    }
} else {
    echo "Nuk ka ndonjë rezultat për vendndodhjet.<br>";
}

// Shfaq të dhënat e faciliteteve
if ($result_facilities->num_rows > 0) {
    echo "<h2>Facilitetet:</h2>";
    while ($row = $result_facilities->fetch_assoc()) {
        echo "Emri: " . htmlspecialchars($row['name']) . "<br>";
        echo "Numri i Telefonit: " . htmlspecialchars($row['phone_number']) . "<br>";
        echo "Lloji: " . htmlspecialchars($row['type']) . "<br>";
        echo "Informacion Kontakti: " . htmlspecialchars($row['contact_info']) . "<br>";
        echo "Foto: <img src='" . htmlspecialchars($row['photo']) . "' alt='Facility Photo' width='100'><br><br>";
    }
} else {
    echo "Nuk ka ndonjë rezultat për facilitete.<br>";
}

// Mbyll lidhjen me bazën e të dhënave
$conn->close();
?>
