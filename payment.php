<?php
require 'db_connection.php'; // Përfshini lidhjen tuaj me DB

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['locations'])) {
        $selected_locations = $_POST['locations']; // Array i ID-ve të zgjedhura
        $total_amount = 0;
        $locations_info = []; // Array për të ruajtur informacionin e vendeve

        // Përllogaritja e shumës totale dhe marrja e informacionit të vendeve
        foreach ($selected_locations as $location_id) {
            $query = "SELECT location_name, location_photo, location_price FROM locations WHERE location_id = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('i', $location_id);
            $stmt->execute();
            $stmt->bind_result($name, $photo, $price);
            $stmt->fetch();

            // Ruajmë informacionin e vendit
            $locations_info[] = [
                'name' => $name,
                'photo' => $photo,
                'price' => $price
            ];
            $total_amount += $price;
            $stmt->close();
        }
    } else {
        header("Location: locations_tirana.php");
        exit();
    }
} else {
    header("Location: locations_tirana.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1>Payment Details</h1>
        
        <h3>Selected Locations:</h3>
        <div class="row">
            <?php foreach ($locations_info as $location) { ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($location['photo']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($location['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($location['name']); ?></h5>
                            <p class="card-text">Price: €<?php echo htmlspecialchars($location['price']); ?></p>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="total-amount-container">
            <label for="total_amount" class="form-label">Total Amount (€)</label>
            <input type="text" class="form-control total-amount" id="total_amount" name="total_amount" value="<?php echo number_format($total_amount, 2); ?>" readonly>
            
            <form action="complete_payment.php" method="POST">
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="bank_account_number" class="form-label">Bank Account Number</label>
                    <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" required>
                </div>
                <div class="mb-3">
                    <label for="credit_card_number" class="form-label">Credit Card Number</label>
                    <input type="text" class="form-control" id="credit_card_number" name="credit_card_number" required>
                </div>
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                <?php foreach ($selected_locations as $location_id) { ?>
                    <input type="hidden" name="locations[]" value="<?php echo htmlspecialchars($location_id); ?>">
                <?php } ?>
                <button type="submit" class="btn btn-primary mt-3">Complete Payment</button>
            </form>
        </div>
    </div>
    <script src="path/to/bootstrap.bundle.min.js"></script>
</body>
</html>
