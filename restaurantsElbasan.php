<?php
// Lidhja me bazën e të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrolloni nëse lidhja është bërë me sukses
if ($conn->connect_error) {
    die("Lidhja dështoi: " . $conn->connect_error);
}

// Merrni restorantet në Elbasan
$sql = "SELECT * FROM facilities WHERE type = 'restaurant' AND city = 'Elbasan'";
$result = $conn->query($sql);

$restaurants = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $restaurants[] = $row;
    }
}

// Mbyllni lidhjen me bazën e të dhënave
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restorantet në Elbasan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carousel-item img {
            object-fit: cover;
            height: 500px; /* Përshtatni sipas nevojave tuaja */
            width: 100%;
        }
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }
        #restaurantCarousel {
            max-width: 80%; /* Përshtatni sipas nevojave */
            margin: 0 auto; /* Qendron në mes të faqes */
        }
        .container {
            text-align: center; /* Qendron tekstin në mes të faqes */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Udhëzuesi për Elbasanin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="barsElbasan.php">Bar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hotelsElbasan.php">Hotelet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="restaurantsElbasan.php">Restorantet</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="taxisElbasan.php">Taksitë</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Restorantet në Elbasan</h1>

        <?php if (!empty($restaurants)): ?>
            <div id="restaurantCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($restaurants as $index => $restaurant): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <a href="<?php echo htmlspecialchars($restaurant['booking_url']); ?>" target="_blank">
                                <img src="<?php echo htmlspecialchars($restaurant['photo']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($restaurant['name']); ?>">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5><?php echo htmlspecialchars($restaurant['name']); ?></h5>
                                    <p><?php echo htmlspecialchars($restaurant['contact_info']); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#restaurantCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">I Kaluar</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#restaurantCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Të Ardhshëm</span>
                </button>
            </div>
        <?php else: ?>
            <p>Asnjë restorant nuk u gjet në Elbasan.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
