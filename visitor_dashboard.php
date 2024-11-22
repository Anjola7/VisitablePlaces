<?php
require 'db_connection.php'; // Përfshini lidhjen tuaj me DB

session_start();
if (!isset($_SESSION['visitor_id'])) {
    header("Location: login.php");
    exit();
}

$visitor_id = $_SESSION['visitor_id'];

// Merrni informacionin e vizitorit nga databaza
$query = "SELECT visited_locations FROM visitors WHERE id = ?";
$stmt = $mysqli->prepare($query);

// Kontrolloni nëse përgatitja e pyetjes ka dështuar
if ($stmt === false) {
    die('MySQL prepare error: ' . htmlspecialchars($mysqli->error));
}

$stmt->bind_param('i', $visitor_id);
$stmt->execute();
$stmt->bind_result($visited_locations_str);
$stmt->fetch();
$stmt->close();

if (empty($visited_locations_str)) {
    echo "No visited locations found in the database for this visitor."; // Debugging
    exit();
}

// Ndajmë ID-të e lokacioneve vizituara
$visited_locations_ids = explode(',', $visited_locations_str);

// Printoni ID-të për debugging
echo "Visited Location IDs: ";
print_r($visited_locations_ids);
echo "<br>";

// Krijojmë një varg për të ruajtur emrat e lokacioneve të vizituara
$visited_locations = [];
if (!empty($visited_locations_ids)) {
    // Merrni emrat e lokacioneve nga tabela locations
    $placeholders = implode(',', array_fill(0, count($visited_locations_ids), '?'));
    $query = "SELECT location_name FROM locations WHERE location_id IN ($placeholders)";
    $stmt = $mysqli->prepare($query);

    // Kontrolloni nëse përgatitja e pyetjes ka dështuar
    if ($stmt === false) {
        die('MySQL prepare error: ' . htmlspecialchars($mysqli->error));
    }

    // Binded the parameters dynamically
    $stmt->bind_param(str_repeat('i', count($visited_locations_ids)), ...$visited_locations_ids);
    $stmt->execute();
    $stmt->bind_result($name);

    while ($stmt->fetch()) {
        $visited_locations[] = $name; // Ruajmë vetëm emrat
    }

    $stmt->close();
}

// Printoni emrat për debugging
echo "Visited Locations: ";
print_r($visited_locations);
echo "<br>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Your Visited Locations</h1>
        <div class="row">
            <?php if (empty($visited_locations)): ?>
                <p>No locations have been visited.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($visited_locations as $location): ?>
                        <li><?php echo htmlspecialchars($location); ?></li> <!-- Shfaqni emrin e vendit -->
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <script src="path/to/bootstrap.bundle.min.js"></script>
</body>
</html>
