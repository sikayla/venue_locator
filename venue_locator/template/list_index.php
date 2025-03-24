<?php 
// Database connection
$host = "localhost";
$user = "root"; // Change if needed
$pass = "";
$dbname = "venue_db"; // Change database name if needed

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch unique categories, prices, and person capacities
$venueTypeQuery = "SELECT DISTINCT category FROM venues";
$venueTypeResult = $conn->query($venueTypeQuery);

$priceQuery = "SELECT DISTINCT category2 FROM venues";
$priceResult = $conn->query($priceQuery);

$personQuery = "SELECT DISTINCT category3 FROM venues";
$personResult = $conn->query($personQuery);

// Fetch all venues
$sql = "SELECT * FROM venues ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Venues</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }
        .filter-dropdown {
            position: relative;
        }
        .row {
    --bs-gutter-x: 1.1rem;
    --bs-gutter-y: 0;
    display: flex;
    flex-wrap: wrap;
    margin-top: calc(-1* var(--bs-gutter-y));
    margin-right: calc(-.5* var(--bs-gutter-x));
    margin-left: calc(-5* var(--bs-gutter-x));
    margin-left: 24%;
}
@media (min-width: 1200px) {
    .h2, h2 {
        font-size: 2rem;
        margin-left: 29.5%;
    }
}
.row.mb-3 {
    margin-top: 2%;
}
.col-md-6 {
    flex: 0 0 auto;
    width: 50%;
    margin-left: 1%;
}
    .sidebar {
    width: 250px;
    background: #383f45;
    padding: 25px;
    height: 1000px;
    position: fixed;
    margin-top: -8%;
    overflow: hidden;
}
    .sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    margin: 15px 0;
    margin-top: 20%;
    font-family: "Roboto", "Helvetica Neue", sans-serif;
     font-size: 20px; font-weight: bold;
}

.btn.btn-secondary.mb-3  {
    margin-left: 30%;
}
    </style>
</head>
<body>

<a href="index.php" class="btn btn-secondary mb-3">← Back to Venues</a>

<h2 class="mb-4">List of Venues</h2>
<div class="sidebar">
    <h3>Ventech Venue</h3>
    <a href="index.php">📍 Map</a>
    <a href="list_index.php">🏢 List Venue</a>
    <a href="enquiries.php">💬 Enquiries</a>
    <a href="booking_index.php">📖 Book</a>
    <br>

</br>
    <a href="signup.php">📝 Register</a>
    </div>

<div class="container mt-4">
    
    
  <!-- Search Bar and Filters -->
<div class="row mb-3">
    <div class="col-md-6">
        <input type="text" class="form-control" id="searchInput" placeholder="Search Venue..." onkeyup="filterVenues()">
    </div>

    
    <!-- Filter Dropdown & Clear Button -->
    <div class="col-md-1 text-end">
        <div class="d-inline-block">
            <div class="dropdown filter-dropdown d-inline">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Filters
                </button> <br> <button class="btn btn-outline-danger ms-2" id="clearFilters" onclick="clearFilters()">Clear</button>
                
                <ul class="dropdown-menu p-3" aria-labelledby="filterDropdown">
                    <li class="mb-2">
                        <label><b>Venue Type:</b></label>
                        <select class="form-select" id="venueTypeFilter" onchange="filterVenues()">
                            <option value="">All</option>
                            <?php while ($row = $venueTypeResult->fetch_assoc()) : ?>
                                <option value="<?= strtolower($row['category']); ?>"><?= $row['category']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </li>
                    <li class="mb-2">
                        <label><b>Price:</b></label>
                        <select class="form-select" id="priceFilter" onchange="filterVenues()">
                            <option value="">All</option>
                            <?php while ($row = $priceResult->fetch_assoc()) : ?>
                                <option value="<?= strtolower($row['category2']); ?>"><?= ucfirst($row['category2']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </li>
                    <li class="mb-2">
                        <label><b>Person Capacity:</b></label>
                        <select class="form-select" id="personFilter" onchange="filterVenues()">
                            <option value="">All</option>
                            <?php while ($row = $personResult->fetch_assoc()) : ?>
                                <option value="<?= strtolower($row['category3']); ?>"><?= $row['category3']; ?> Persons</option>
                            <?php endwhile; ?>
                        </select>
                    </li>
                </ul>
            </div>

            <!-- Clear Button -->
           
        </div>
    </div>
</div>




    <!-- Venue Listings -->
    <div class="row" id="venueList">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="col-md-3 mb-4 venue-card" 
                data-category="<?= strtolower($row['category']); ?>"
                data-price="<?= strtolower($row['category2']); ?>"
                data-person="<?= strtolower($row['category3']); ?>">
                <div class="card">
                    <img src="<?= $row['image']; ?>" class="card-img-top" alt="<?= $row['name']; ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <span class="badge bg-primary"><?= $row['category']; ?></span>
                        <span class="badge bg-success"><?= ucfirst($row['category2']); ?></span>
                        <span class="badge bg-info"><?= $row['category3']; ?> Persons</span>
                        <h5 class="card-title mt-2"><?= $row['name']; ?></h5>
                        <p class="card-text"><?= substr($row['description'], 0, 100); ?>...</p>
                        <h6 class="text-dark fw-bold">₱<?= number_format($row['price'], 2); ?></h6>
                        <a href="venue_details.php?id=<?= $row['id'] ?>" class="btn btn-primary">VIEW VENUE</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>


<script>

function clearFilters() {
    document.getElementById('searchInput').value = "";
    document.getElementById('venueTypeFilter').value = "";
    document.getElementById('priceFilter').value = "";
    document.getElementById('personFilter').value = "";
    filterVenues(); // Refresh the venue list
}

function filterVenues() {
    let searchInput = document.getElementById('searchInput').value.toLowerCase();
    let venueType = document.getElementById('venueTypeFilter').value.toLowerCase();
    let price = document.getElementById('priceFilter').value.toLowerCase();
    let person = document.getElementById('personFilter').value.toLowerCase();

    let cards = document.getElementsByClassName('venue-card');

    for (let i = 0; i < cards.length; i++) {
        let titleElement = cards[i].getElementsByClassName('card-title')[0];
        let title = titleElement ? titleElement.innerText.toLowerCase() : "";

        let cardCategory = cards[i].getAttribute('data-category');
        let cardPrice = cards[i].getAttribute('data-price');
        let cardPerson = cards[i].getAttribute('data-person');

        let matchTitle = title.includes(searchInput);
        let matchCategory = venueType === "" || cardCategory === venueType;
        let matchPrice = price === "" || cardPrice === price;
        let matchPerson = person === "" || cardPerson === person;

        if (matchTitle && matchCategory && matchPrice && matchPerson) {
            cards[i].style.display = "block";
        } else {
            cards[i].style.display = "none";
        }
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php $conn->close(); ?>
