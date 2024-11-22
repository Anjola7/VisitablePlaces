<?php
require 'db_connection.php'; // Përfshini lidhjen tuaj me DB

// Marrja e të gjitha vendndodhjeve që janë të lidhura me Lezhën
$query = "SELECT * FROM locations WHERE location_city = 'Lezhe'";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locations in Lezhe</title>
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <style>
        body {
            background-color: #ede1e1; /* Ngjyrë sfondi e lehtë */
        }

        .container {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff; /* Sfondi i bardhë për përmbajtjen */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50; /* Ngjyra e titullit */
            text-align: center;
        }

        .location-card {
            margin-bottom: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            padding: 1rem;
            display: flex;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s; /* Animacion për efektin */
        }

        .location-card:hover {
            transform: scale(1.02); /* Zmadho pak kur kalon mbi të */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Hidhni hije */
        }

        .location-card img {
            width: 100px; /* Gjerësia e imazhit */
            height: 100px; /* Lartësia e imazhit */
            object-fit: cover; /* Imazhi të mbushë zonën pa deformuar */
            margin-right: 1rem; /* Hapësira midis imazhit dhe tekstin */
            border-radius: 10px; /* Këndet e rrumbullakosura për imazhin */
        }

        .location-card-body {
            flex-grow: 1; /* Lejon që trupi të zgjasë */
        }

        .location-card-body h5 {
            margin: 0; /* Hiq hapësirën e brendshme për titujt */
            color: #34495e; /* Ngjyra e titujve */
        }

        .btn-primary {
            background-color: #007bff; /* Ngjyra e butonit */
            border: none;
            border-radius: 5px;
            font-size: 1.25rem; /* Zmadho tekstin e butonit */
            padding: 10px 20px; /* Rrit hapësirën brenda butonit */
            margin-top: 20px; /* Hapësira lart */
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Ngjyra kur kalon miu */
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Locations in Lezhe</h1>
        <form action="process_selections.php" method="POST">
            <div class="list-group">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="location-card">
                        <img src="<?php echo htmlspecialchars($row['location_photo']); ?>" alt="<?php echo htmlspecialchars($row['location_name']); ?>">
                        <div class="location-card-body">
                            <h5><?php echo htmlspecialchars($row['location_name']); ?></h5>
                            <p>Price: €<?php echo htmlspecialchars($row['location_price']); ?></p>
                            <input type="checkbox" name="locations[]" value="<?php echo $row['location_id']; ?>" data-price="<?php echo $row['location_price']; ?>" class="location-checkbox"> Select
                        </div>
                    </div>
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-primary">Submit Selections</button>
        </form>
    </div>

    <script src="path/to/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.location-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    let total = 0;
                    checkboxes.forEach(box => {
                        if (box.checked) {
                            total += parseFloat(box.getAttribute('data-price'));
                        }
                    });
                    console.log('Total: €' + total.toFixed(2)); // Mund të përdoret për të testuar
                });
            });
        });
    </script>
</body>
</html>
