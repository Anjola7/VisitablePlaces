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

// Marrim të dhënat e vendndodhjeve
$sql = "SELECT location_id, location_name, location_city, location_photo FROM locations";
$result = $conn->query($sql);

if (!$result) {
    die("Gabim në kërkesën SQL: " . $conn->error);
}

// Marrim të dhënat e facilitetet
$sql_facilities = "SELECT id, name, photo, phone_number, type, contact_info, city FROM facilities";
$result_facilities = $conn->query($sql_facilities);

if (!$result_facilities) {
    die("Gabim në kërkesën SQL për facilitetet: " . $conn->error);
}

// Procesi i shtimit të menaxherëve
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_manager'])) {
    $manager_username = $_POST['manager_username'];
    $manager_password = password_hash($_POST['manager_password'], PASSWORD_DEFAULT);
    $city = $_POST['city'];

    if (!empty($manager_username) && !empty($manager_password) && !empty($city)) {
        $sql = "INSERT INTO users (username, user_password, user_role, user_city) 
                VALUES (?, ?, 'manager', ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $manager_username, $manager_password, $city);

            if ($stmt->execute()) {
                echo "<p class='text-success'>Menaxheri është shtuar me sukses!</p>";
            } else {
                echo "<p class='text-danger'>Gabim gjatë shtimit të menaxherit: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p class='text-danger'>Gabim në përgatitjen e deklaratës SQL: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='text-danger'>Ju lutem plotesoni të gjitha fushat.</p>";
    }
}

// Procesi i modifikimit të vendndodhjeve
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_location'])) {
    $location_id = $_POST['location_id'];
    $location_name = $_POST['location_name'];
    $location_city = $_POST['location_city'];

    $sql = "UPDATE locations SET location_name=?, location_city=? WHERE location_id=?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $location_name, $location_city, $location_id);

        if ($stmt->execute()) {
            echo "<p class='text-success'>Vendndodhja u modifikua me sukses!</p>";
        } else {
            echo "<p class='text-danger'>Gabim gjatë modifikimit të vendndodhjes: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p class='text-danger'>Gabim në përgatitjen e deklaratës SQL: " . $conn->error . "</p>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.3/datatables.min.css" rel="stylesheet">

    <title>Admin Dashboard - Visitable Places</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        h1 {
            margin-bottom: 30px;
            color: #343a40;
        }

        #datatable, #datatable_facilities {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        #datatable thead, #datatable_facilities thead {
            background-color: #343a40;
            color: white;
        }

        #datatable thead th, #datatable_facilities thead th {
            padding: 10px;
            text-align: center;
        }

        #datatable tbody td, #datatable_facilities tbody td {
            padding: 10px;
            text-align: center;
        }

        #datatable tbody tr:nth-child(even), #datatable_facilities tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #datatable tbody tr:hover, #datatable_facilities tbody tr:hover {
            background-color: #e9ecef;
        }

        .btn {
            margin-right: 5px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn + a {
            margin-right: 5px;
            padding: 5px 10px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn + a:hover {
            background-color: #c82333;
        }

        .photo-img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1 class="text-center">Admin Dashboard - Visitable Places</h1>

    <!-- Tabela për të shfaqur vendndodhjet -->
    <h2>Vendndodhjet</h2>
    <table id="datatable" class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Emri i Vendndodhjes</th>
                <th>Qyteti</th>
                <th>Foto</th>
                <th>Modifiko</th>
                <th>Fshi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['location_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['location_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['location_city']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['location_photo']); ?>" alt="Foto" class="photo-img"></td>
                    <td><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editLocationModal" data-id="<?php echo $row['location_id']; ?>" data-name="<?php echo $row['location_name']; ?>" data-city="<?php echo $row['location_city']; ?>" data-photo="<?php echo $row['location_photo']; ?>">Modifiko</button></td>
                    <td><a href="delete1_location.php?id=<?php echo $row['location_id']; ?>" class="btn btn-danger">Fshi</a></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Tabela për të shfaqur facilitetet -->
    <!-- Tabela për të shfaqur facilitetet -->
<h2>Facilitetet</h2>
<table id="datatable_facilities" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Emri</th>
            <th>Foto</th>
            <th>Numri i Telefonit</th>
            <th>Tipi</th>
            <th>Informacion Kontakti</th>
            <th>Qyteti</th>
            <th>Actions</th> <!-- Kolona e re për butonat -->
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result_facilities->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Foto" class="photo-img"></td>
                <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                <td><?php echo htmlspecialchars($row['type']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
                <td><?php echo htmlspecialchars($row['city']); ?></td>
                <td>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editFacilityModal" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" data-photo="<?php echo $row['photo']; ?>" data-phone="<?php echo $row['phone_number']; ?>" data-type="<?php echo $row['type']; ?>" data-contact="<?php echo $row['contact_info']; ?>" data-city="<?php echo $row['city']; ?>">Modifiko</button>
                    <a href="delete1_facility.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Fshi</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


    <!-- Modal për të modifikuar vendndodhjen -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLocationModalLabel">Modifiko Vendndodhjen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="update_location.php" enctype="multipart/form-data">
                    <input type="hidden" id="location_id" name="location_id">
                    <div class="mb-3">
                        <label for="location_name" class="form-label">Emri i Vendndodhjes</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="location_city" class="form-label">Qyteti</label>
                        <input type="text" class="form-control" id="location_city" name="location_city" required>
                    </div>
                    <div class="mb-3">
                        <label for="location_photo" class="form-label">Foto e Re</label>
                        <input type="file" class="form-control" id="location_photo" name="location_photo">
                    </div>
                    <button type="submit" name="edit_location" class="btn btn-primary">Ruaj Ndryshimet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal për të modifikuar facilitetet -->
<div class="modal fade" id="editFacilityModal" tabindex="-1" aria-labelledby="editFacilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFacilityModalLabel">Modifiko Facilitete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="update_facility.php" enctype="multipart/form-data">
                    <input type="hidden" id="facility_id" name="facility_id">
                    <div class="mb-3">
                        <label for="facility_name" class="form-label">Emri</label>
                        <input type="text" class="form-control" id="facility_name" name="facility_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="facility_photo" class="form-label">Foto e Re</label>
                        <input type="file" class="form-control" id="facility_photo" name="facility_photo">
                    </div>
                    <div class="mb-3">
                        <label for="facility_phone" class="form-label">Numri i Telefonit</label>
                        <input type="text" class="form-control" id="facility_phone" name="facility_phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="facility_type" class="form-label">Tipi</label>
                        <input type="text" class="form-control" id="facility_type" name="facility_type" required>
                    </div>
                    <div class="mb-3">
                        <label for="facility_contact" class="form-label">Informacion Kontakti</label>
                        <input type="text" class="form-control" id="facility_contact" name="facility_contact" required>
                    </div>
                    <div class="mb-3">
                        <label for="facility_city" class="form-label">Qyteti</label>
                        <input type="text" class="form-control" id="facility_city" name="facility_city" required>
                    </div>
                    <div class="mb-3">
                        <label for="facility_booking" class="form-label">Booking url</label>
                        <input type="text" class="form-control" id="facility_booking" name="facility_booking" required>
                    </div>
                    <button type="submit" name="edit_facility" class="btn btn-primary">Ruaj Ndryshimet</button>
                </form>
            </div>
        </div>
    </div>
</div>



    <!-- Forma për të shtuar menaxherë -->
    <h2>Shto Menaxher</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="manager_username" class="form-label">Emri i Përdoruesit</label>
            <input type="text" class="form-control" id="manager_username" name="manager_username" required>
        </div>
        <div class="mb-3">
            <label for="manager_password" class="form-label">Fjalëkalimi</label>
            <input type="password" class="form-control" id="manager_password" name="manager_password" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Qyteti</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <button type="submit" name="add_manager" class="btn btn-success">Shto Menaxher</button>
    </form>

    <!-- Bootstrap JavaScript dhe DataTables JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.3/datatables.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializo DataTable për tabelën e vendndodhjeve
            $('#datatable').DataTable();

            // Inicializo DataTable për tabelën e facilitetet
            $('#datatable_facilities').DataTable();

            // Populloj të dhënat në modalin për modifikimin e vendndodhjes
            var editLocationModal = document.getElementById('editLocationModal');
            editLocationModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var locationId = button.getAttribute('data-id');
                var locationName = button.getAttribute('data-name');
                var locationCity = button.getAttribute('data-city');
                var locationPhoto = button.getAttribute('data-photo');

                var modal = this;
                modal.querySelector('#location_id').value = locationId;
                modal.querySelector('#location_name').value = locationName;
                modal.querySelector('#location_city').value = locationCity;
            });
        });
    </script>
</body>
</html>
