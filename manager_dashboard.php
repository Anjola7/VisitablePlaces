<?php
session_start(); // Aktivizo seancat

// Kontrollo nëse përdoruesi është loguar dhe ka rol menaxher
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Krijo lidhjen me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$mysqli = new mysqli($servername, $username, $password, $dbname);

// Kontrollo nëse lidhja është e suksesshme
if ($mysqli->connect_error) {
    die("Lidhja me bazën e të dhënave dështoi: " . $conn->connect_error);
}

// Kontrollo për formën e POST-it për të shtuar vendndodhje ose facilitete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_location'])) {
        $location_name = $_POST['new_location_name'];
        $location_city = $_POST['new_location_city'];
        $location_price=$_POST['location_price'];
        $description= $_POST['description'];
        $location_photo = '';
       

        if (!empty($_FILES['new_location_photo']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["new_location_photo"]["name"]);
            move_uploaded_file($_FILES["new_location_photo"]["tmp_name"], $target_file);
            $location_photo = $target_file;
        }

        $sql_insert_location = "INSERT INTO locations (location_name, location_city,location_price,description, location_photo) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_location = $mysqli->prepare($sql_insert_location);
      
$stmt_insert_location->bind_param("sssss", $location_name, $location_city, $location_price, $description, $location_photo);

        $stmt_insert_location->execute();
    }

    if (isset($_POST['add_facility'])) {
        $facility_name = $_POST['new_facility_name'];
        $facility_phone_number = $_POST['new_facility_phone_number'];
        $facility_type = $_POST['new_facility_type'];
        $facility_contact_info = $_POST['new_facility_contact_info'];
        $facility_city = $_POST['new_facility_city'];
        $facility_booking_url = $_POST['new_facility_booking_url'];
        $facility_photo = '';

        if (!empty($_FILES['new_facility_photo']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["new_facility_photo"]["name"]);
            move_uploaded_file($_FILES["new_facility_photo"]["tmp_name"], $target_file);
            $facility_photo = $target_file;
        }

        $sql_insert_facility = "INSERT INTO facilities (name, phone_number, type, contact_info, photo, city, booking_url) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_facility = $mysqli->prepare($sql_insert_facility);
        $stmt_insert_facility->bind_param("sssssss", $facility_name, $facility_phone_number, $facility_type, $facility_contact_info, $facility_photo, $facility_city, $facility_booking_url);
        $stmt_insert_facility->execute();
    }

    if (isset($_POST['edit_location'])) {
        $location_id = $_POST['location_id'];
        $location_name = $_POST['location_name'];
        $location_city = $_POST['location_city'];
        $location_price = $_POST['location_price'];
        $description = $_POST['description'];
        $location_photo = '';

        if (!empty($_FILES['location_photo']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["location_photo"]["name"]);
            move_uploaded_file($_FILES["location_photo"]["tmp_name"], $target_file);
            $location_photo = $target_file;
        }

        $sql_update_location = "UPDATE locations SET location_name = ?, location_city = ?, location_description = ? , description= ? , location_photo = ? WHERE location_id = ?";
        $stmt_update_location = $conn->prepare($sql_update_location);
        $stmt_update_location->bind_param("sssi", $location_name, $location_city, $location_price, $description, $location_photo, $location_id);
        $stmt_update_location->execute();
    }

    if (isset($_POST['edit_facility'])) {
        $facility_id = $_POST['facility_id'];
        $facility_name = $_POST['facility_name'];
        $facility_phone_number = $_POST['facility_phone_number'];
        $facility_type = $_POST['facility_type'];
        $facility_contact_info = $_POST['facility_contact_info'];
        $facility_city = $_POST['facility_city'];
        $facility_booking_url = $_POST['facility_booking_url'];
        $facility_photo = '';

        if (!empty($_FILES['facility_photo']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["facility_photo"]["name"]);
            move_uploaded_file($_FILES["facility_photo"]["tmp_name"], $target_file);
            $facility_photo = $target_file;
        }

        $sql_update_facility = "UPDATE facilities SET name = ?, phone_number = ?, type = ?, contact_info = ?, photo = ?, city = ?, booking_url = ? WHERE id = ?";
        $stmt_update_facility = $conn->prepare($sql_update_facility);
        $stmt_update_facility->bind_param("sssssssi", $facility_name, $facility_phone_number, $facility_type, $facility_contact_info, $facility_photo, $facility_city, $facility_booking_url, $facility_id);
        $stmt_update_facility->execute();
    }
}

// Marrim emrin e qytetit të lidhur me menaxherin e loguar
$manager_city = $_SESSION['user_city']; // Përdorim emrin e qytetit të menaxherit

// Marrim të dhënat e lokacioneve që i përkasin menaxherit
$sql_locations = "SELECT location_id, location_name, location_city, location_price , description ,  location_photo FROM locations WHERE location_city = ?";
$stmt_locations = $mysqli->prepare($sql_locations);
$stmt_locations->bind_param("s", $manager_city); // Përdorim 's' për string
$stmt_locations->execute();
$result_locations = $stmt_locations->get_result();

// Marrim të dhënat e faciliteteve që janë të lidhura me qytetin e menaxherit
$sql_facilities = "SELECT id, name, phone_number, type, contact_info, photo, city, booking_url FROM facilities WHERE city = ?";
$stmt_facilities = $mysqli->prepare($sql_facilities);
$stmt_facilities->bind_param("s", $manager_city); // Përdorim 's' për string
$stmt_facilities->execute();
$result_facilities = $stmt_facilities->get_result();

$mysqli->close();
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

    <title>Manager Dashboard - Visitable Places</title>

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

        #datatable, #facilities_table {
            width: 100%;
            border-collapse: collapse;
        }

        #datatable thead, #facilities_table thead {
            background-color: #343a40;
            color: white;
        }

        #datatable thead th, #facilities_table thead th {
            padding: 10px;
            text-align: center;
        }

        #datatable tbody td, #facilities_table tbody td {
            padding: 10px;
            text-align: center;
        }

        .btn {
            margin-right: 10px;
        }

        .modal-content {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            margin-bottom: 5px;
            display: block;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>Manager Dashboard - Visitable Places</h1>

    <div class="container">
        <!-- Form to add new location -->
        <h3>Add New Location</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="new_location_name">Name</label>
                <input type="text" id="new_location_name" name="new_location_name" required>
            </div>
            <div class="form-group">
                <label for="new_location_city">City</label>
                <input type="text" id="new_location_city" name="new_location_city" required>
            </div>
            <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>

        <label for="location_price">Price:</label>
        <input type="number" id="location_price" name="location_price" step="0.01" required><br><br>

            <div class="form-group">
                <label for="new_location_photo">Photo</label>
                <input type="file" id="new_location_photo" name="new_location_photo">
            </div>
            <button type="submit" name="add_location" class="btn btn-primary">Add Location</button>
        </form>

        <!-- Form to add new facility -->
        <h3>Add New Facility</h3>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="new_facility_name">Name</label>
                <input type="text" id="new_facility_name" name="new_facility_name" required>
            </div>
            <div class="form-group">
                <label for="new_facility_phone_number">Phone Number</label>
                <input type="text" id="new_facility_phone_number" name="new_facility_phone_number" required>
            </div>
            <div class="form-group">
                <label for="new_facility_type">Type</label>
                <input type="text" id="new_facility_type" name="new_facility_type" required>
            </div>
            <div class="form-group">
                <label for="new_facility_contact_info">Contact Info</label>
                <input type="text" id="new_facility_contact_info" name="new_facility_contact_info" required>
            </div>
            <div class="form-group">
                <label for="new_facility_city">City</label>
                <input type="text" id="new_facility_city" name="new_facility_city" required>
            </div>
            <div class="form-group">
                <label for="new_facility_booking_url">Booking URL</label>
                <input type="text" id="new_facility_booking_url" name="new_facility_booking_url" required>
            </div>
            <div class="form-group">
                <label for="new_facility_photo">Photo</label>
                <input type="file" id="new_facility_photo" name="new_facility_photo">
            </div>
            <button type="submit" name="add_facility" class="btn btn-primary">Add Facility</button>
        </form>

       <!-- Locations Table -->
<h3>Manage Locations</h3>
<table id="datatable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>City</th>
            <th>Price</th>
            <th>Description</th>
            <th>Photo</th>
            <th>Actions</th>

        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result_locations->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['location_id']); ?></td>
            <td><?php echo htmlspecialchars($row['location_name']); ?></td>
            <td><?php echo htmlspecialchars($row['location_city']); ?></td>
            <td><?php echo htmlspecialchars($row['location_price']); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td><img src="<?php echo htmlspecialchars($row['location_photo']); ?>" alt="Photo" width="100"></td>
            <td>
                <button class="btn btn-warning btn-edit-location" 
                        data-id="<?php echo htmlspecialchars($row['location_id']); ?>" 
                        data-name="<?php echo htmlspecialchars($row['location_name']); ?>" 
                        data-city="<?php echo htmlspecialchars($row['location_city']); ?>" 
                        data-price="<?php echo htmlspecialchars($row['location_price']); ?>" 
                        data-description="<?php echo htmlspecialchars($row['description']); ?>" 
                        data-photo="<?php echo htmlspecialchars($row['location_photo']); ?>" 
                        data-bs-toggle="modal" data-bs-target="#editLocationModal">Edit</button>
                <button class="btn btn-danger btn-delete-location" data-id="<?php echo htmlspecialchars($row['location_id']); ?>">Delete</button>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Facilities Table -->
<h3>Manage Facilities</h3>
<table id="facilities_table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Type</th>
            <th>Contact Info</th>
            <th>City</th>
            <th>Booking URL</th>
            <th>Photo</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result_facilities->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
            <td><?php echo htmlspecialchars($row['type']); ?></td>
            <td><?php echo htmlspecialchars($row['contact_info']); ?></td>
            <td><?php echo htmlspecialchars($row['city']); ?></td>
            <td><a href="<?php echo htmlspecialchars($row['booking_url']); ?>" target="_blank">Booking Link</a></td>
            <td><img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Photo" width="100"></td>
            <td>
                <button class="btn btn-warning btn-edit-facility" 
                        data-id="<?php echo htmlspecialchars($row['id']); ?>" 
                        data-name="<?php echo htmlspecialchars($row['name']); ?>" 
                        data-phone_number="<?php echo htmlspecialchars($row['phone_number']); ?>" 
                        data-type="<?php echo htmlspecialchars($row['type']); ?>" 
                        data-contact_info="<?php echo htmlspecialchars($row['contact_info']); ?>" 
                        data-city="<?php echo htmlspecialchars($row['city']); ?>" 
                        data-booking_url="<?php echo htmlspecialchars($row['booking_url']); ?>" 
                        data-photo="<?php echo htmlspecialchars($row['photo']); ?>" 
                        data-bs-toggle="modal" data-bs-target="#editFacilityModal">Edit</button>
              <button class="btn btn-danger btn-delete-facility" data-id="<?php echo htmlspecialchars($row['id']); ?>">Delete</button>
                    </td>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<!-- Modal for Editing Location -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="edit_location.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLocationModalLabel">Edit Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="location_id" name="location_id">
                    <input type="hidden" id="existing_photo" name="existing_photo">

                    <div class="form-group">
                        <label for="location_name">Name</label>
                        <input type="text" id="location_name" name="location_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="location_city">City</label>
                        <input type="text" id="location_city" name="location_city" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="location_price">Price</label>
                        <input type="number" id="location_price" name="location_price" class="form-control" step="0.01" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="location_photo">Photo</label>
                        <input type="file" id="location_photo" name="location_photo" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="location_description">Description</label>
                        <textarea id="location_description" name="location_description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_location" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal for Editing Facility -->
<div class="modal fade" id="editFacilityModal" tabindex="-1" aria-labelledby="editFacilityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="edit_facility.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFacilityModalLabel">Edit Facility</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="facility_id" name="facility_id">
                    <input type="hidden" id="existing_photo" name="existing_photo">
                    <div class="form-group">
                        <label for="facility_name">Name</label>
                        <input type="text" id="facility_name" name="facility_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="facility_phone_number">Phone Number</label>
                        <input type="text" id="facility_phone_number" name="facility_phone_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="facility_type">Type</label>
                        <input type="text" id="facility_type" name="facility_type" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="facility_contact_info">Contact Info</label>
                        <input type="text" id="facility_contact_info" name="facility_contact_info" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="facility_city">City</label>
                        <input type="text" id="facility_city" name="facility_city" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="facility_booking_url">Booking URL</label>
                        <input type="text" id="facility_booking_url" name="facility_booking_url" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="facility_photo">Photo</label>
                        <input type="file" id="facility_photo" name="facility_photo" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="edit_facility" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.3/datatables.min.js"></script>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
    $('#datatable').DataTable();
    $('#facilities_table').DataTable();

    // Fill the edit modal with data for locations
    $('.btn-edit-location').click(function() {
        var location_id = $(this).data('id');
        var location_name = $(this).data('name');
        var location_city = $(this).data('city');
        var location_price = $(this).data('price'); // Added line for price
        var location_description = $(this).data('description'); // Added line for description
        var location_photo = $(this).data('photo');

        $('#editLocationModal #location_id').val(location_id);
        $('#editLocationModal #location_name').val(location_name);
        $('#editLocationModal #location_city').val(location_city);
        $('#editLocationModal #location_price').val(location_price); // Set price
        $('#editLocationModal #location_description').val(location_description); // Set description
        $('#editLocationModal #existing_photo').val(location_photo);
    });


        // Fill the edit modal with data for facilities
        $('.btn-edit-facility').click(function() {
            var facility_id = $(this).data('id');
            var facility_name = $(this).data('name');
            var facility_phone_number = $(this).data('phone_number');
            var facility_type = $(this).data('type');
            var facility_contact_info = $(this).data('contact_info');
            var facility_city = $(this).data('city');
            var facility_booking_url = $(this).data('booking_url');
            var facility_photo = $(this).data('photo');
            $('#editFacilityModal #facility_id').val(facility_id);
            $('#editFacilityModal #facility_name').val(facility_name);
            $('#editFacilityModal #facility_phone_number').val(facility_phone_number);
            $('#editFacilityModal #facility_type').val(facility_type);
            $('#editFacilityModal #facility_contact_info').val(facility_contact_info);
            $('#editFacilityModal #facility_city').val(facility_city);
            $('#editFacilityModal #facility_booking_url').val(facility_booking_url);
            $('#editFacilityModal #existing_photo').val(facility_photo);
        });

        // Delete confirmation for locations
        $('.btn-delete-location').click(function() {
            var location_id = $(this).data('id');
            if (confirm("Are you sure you want to delete this location?")) {
                window.location.href = 'delete_location.php?id=' + location_id;
            }
        });

        // Delete confirmation for facilities
        $('.btn-delete-facility').click(function() {
            var facility_id = $(this).data('id');
            if (confirm("Are you sure you want to delete this facility?")) {
                window.location.href = 'delete_facility.php?id=' + facility_id;
            }
        });
    });
</script>
