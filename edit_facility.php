<?php
include 'db_connection.php'; // Lidhja me bazën e të dhënave

if (isset($_POST['edit_facility'])) {
    $facility_id = $_POST['facility_id'];
    $facility_name = $_POST['facility_name'];
    $facility_phone_number = $_POST['facility_phone_number'];
    $facility_type = $_POST['facility_type'];
    $facility_contact_info = $_POST['facility_contact_info'];
    $facility_city = $_POST['facility_city'];
    $facility_booking_url = $_POST['facility_booking_url'];
    $existing_photo = $_POST['existing_photo'];

    // Kontrollo dhe ngarko foton e re nëse ekziston
    if (!empty($_FILES['facility_photo']['name'])) {
        $photo = 'uploads/' . basename($_FILES['facility_photo']['name']);
        move_uploaded_file($_FILES['facility_photo']['tmp_name'], $photo);
    } else {
        $photo = $existing_photo;
    }

    $query = "UPDATE facilities SET name = ?, phone_number = ?, type = ?, contact_info = ?, city = ?, booking_url = ?, photo = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssssssi', $facility_name, $facility_phone_number, $facility_type, $facility_contact_info, $facility_city, $facility_booking_url, $photo, $facility_id);

    if ($stmt->execute()) {
      
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
