<?php
// Connection to database
include 'db_connection.php';

// SQL query to fetch locations where city is 'Tirana'
$sql = "SELECT * FROM locations WHERE location_city = 'Tirana'";
$result = $conn->query($sql);

$locations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}

$conn->close();
?>
