<?php
require 'db_connection.php'; // Përfshini lidhjen tuaj me DB

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kontrolloni nëse janë dërguar të dhënat e lokacioneve
    if (isset($_POST['locations'])) {
        $selected_locations = $_POST['locations']; // Array i ID-ve të zgjedhura
        $total_amount = $_POST['total_amount']; // Shuma totale

        // Merrni të dhënat nga formulari
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $bank_account_number = $_POST['bank_account_number'];
        $credit_card_number = $_POST['credit_card_number'];
        $password = $_POST['password']; // Shtohet passwordi

        // Regjistroni vizitorin në tabelën visitors
        $query = "INSERT INTO visitors (first_name, last_name, password) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hashimi i password-it
        $stmt->bind_param('sss', $first_name, $last_name, $password_hash);

        if ($stmt->execute()) {
            // Merrni ID-në e vizitorit të sapo regjistruar
            $visitor_id = $stmt->insert_id;

            // Regjistroni detajet e bankës në tabelën bankdetails
            $query_bank = "INSERT INTO bankdetails (bank_account_number, credit_card_number, visitor_id) VALUES (?, ?, ?)";
            $stmt_bank = $mysqli->prepare($query_bank);
            $stmt_bank->bind_param('ssi', $bank_account_number, $credit_card_number, $visitor_id);
            $stmt_bank->execute();

            // Regjistroni lokacionet e vizituara
            foreach ($selected_locations as $location_id) {
                $query_visited = "INSERT INTO visited_locations (visitor_id, location_id) VALUES (?, ?)";
                $stmt_visited = $mysqli->prepare($query_visited);
                $stmt_visited->bind_param('ii', $visitor_id, $location_id);
                $stmt_visited->execute();
                $stmt_visited->close();
            }

            // Mbyllni deklaratat
            $stmt->close();
            $stmt_bank->close();

            // Redirigjoni përdoruesin në një faqe suksesi
            header("Location: success_page.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
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
    <style>
        /* Stil i njohur për kartat dhe elementet e faqeve */
        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .card-img-top {
            width: 100px; /* Gjerësia e re */
            height: 100px; /* Lartësia e re */
            margin: auto;
            border-radius: 10px;
            object-fit: cover; /* Ruajmë përmasat e imazhit */
        }
        .card-body {
            background-color: #f8f9fa;
            text-align: center;
        }
        .total-amount-container {
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            font-size: 1.25rem;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .total-amount {
            font-size: 2rem;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Payment Details</h1>

        <h3>Selected Locations:</h3>
        <div class="row">
            <?php foreach ($selected_locations as $location_id) {
                // Merrni informacionin për lokacionin
                $query_location = "SELECT location_name, location_photo, location_price FROM locations WHERE location_id = ?";
                $stmt_location = $mysqli->prepare($query_location);
                $stmt_location->bind_param('i', $location_id);
                $stmt_location->execute();
                $stmt_location->bind_result($name, $photo, $price);
                $stmt_location->fetch();
            ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($photo); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($name); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($name); ?></h5>
                            <p class="card-text">Price: €<?php echo htmlspecialchars($price); ?></p>
                        </div>
                    </div>
                </div>
            <?php
                $stmt_location->close();
            } ?>
        </div>

        <div class="total-amount-container">
            <label for="total_amount" class="form-label">Total Amount (€)</label>
            <input type="text" class="form-control total-amount" id="total_amount" name="total_amount" value="<?php echo number_format($total_amount, 2); ?>" readonly>
            <form action="complete_payment.php" method="POST">
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                <?php foreach ($selected_locations as $location_id) { ?>
                    <input type="hidden" name="locations[]" value="<?php echo htmlspecialchars($location_id); ?>">
                <?php } ?>
                
                <!-- Formë për të dhënat e përdoruesit -->
                <h3>User Information</h3>
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

                <button type="submit" class="btn btn-primary mt-3">Complete Payment</button>
            </form>
        </div>
    </div>
    <script src="path/to/bootstrap.bundle.min.js"></script>
</body>
</html>
