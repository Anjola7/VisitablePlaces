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

// Merrni atraksionet në Durrës
$sql = "SELECT * FROM locations WHERE location_city = 'Durres'";
$result = $conn->query($sql);

$attractions = [];
if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $attractions[] = $row;
        }
    }
} else {
    echo "Gabim në ekzekutimin e kërkesës: " . $conn->error;
}

// Mbyllni lidhjen me bazën e të dhënave
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Durres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .carousel-item img {
            max-width: 80%;
            max-height: 400px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin: 0 auto;
        }
        .carousel-caption {
            position: relative;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            background-color: rgba(200, 200, 200, 0.8);
            color: #000;
            text-align: center;
            margin-top: 1rem;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
            z-index: 1000;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
        .sidebar h2 {
            font-size: 1.5rem;
        }
        .sidebar a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #007bff;
        }
        .sidebar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light" style="z-index: 1001;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Durrësi Explorer</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="test.html">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Attractions
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php include 'fetchgjr_locations.php'; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="locations_durres.php">Buy ticket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Help</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="sidebar">
    <h2>Explore Durrës</h2>
    <a href="hotelsDurres.php">Hotels</a>
    <a href="restaurantsDurres.php">Restaurants</a>
    <a href="taxisDurres.php">Taxis</a>
    <a href="barsDurres.php">Bars</a>
</div>

<div class="main-content">
    <h1>Durrës</h1>
    <div id="carouselExampleFade" class="carousel slide carousel-fade mt-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php if (!empty($attractions)): ?>
                <?php foreach ($attractions as $index => $attraction): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo htmlspecialchars($attraction['location_photo']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($attraction['location_name']); ?>">
                        <div class="carousel-caption d-none d-md-block">
                            <h5><?php echo htmlspecialchars($attraction['location_name']); ?></h5>
                            <p><?php echo htmlspecialchars($attraction['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Asnjë atraksion nuk u gjet në Durrës.</p>
            <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

