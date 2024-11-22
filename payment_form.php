<?php
// Inicializimi i variablave nëse nuk janë caktuar
if (!isset($locations_info)) {
    $locations_info = [];
}

if (!isset($selected_locations)) {
    $selected_locations = [];
}

$total_amount = 0.00;

// Llogaritja e shumës totale bazuar në vendet e përzgjedhura
foreach ($locations_info as $location) {
    if (in_array($location['id'], $selected_locations)) {
        $total_amount += $location['price'];
    }
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
            width: 30%;
            height: auto;
            margin: auto;
            border-radius: 10px;
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
            <?php foreach ($locations_info as $location) { ?>
                <?php if (in_array($location['id'], $selected_locations)) { ?>
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
            <?php } ?>
        </div>

        <div class="total-amount-container">
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
                <div class="mb-3">
                    <label for="total_amount" class="form-label">Total Amount (€)</label>
                    <input type="text" class="form-control total-amount" id="total_amount" name="total_amount" value="<?php echo number_format($total_amount, 2); ?>" readonly>
                </div>
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
