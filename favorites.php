<?php
session_start();

include('db.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$username = $_SESSION['username'];


$query = $conn->prepare("SELECT favorites FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();
$user_favorites = $row['favorites'] ? explode(",", $row['favorites']) : [];


$favorites_exist = !empty($user_favorites);


if ($favorites_exist) {
    $favorites_str = implode(",", $user_favorites);
    $query = "SELECT * FROM adoptions WHERE id IN ($favorites_str)";
    $result = $conn->query($query);
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites Page - Pet Match</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <input type="checkbox" name="" id="toggler">
        <label for="toggler" class="fas fa-bars"></label>
        <div class="titlu">
            <a href="#" class="fas fa-paw"></a>
            <a href="#" class="logo">Pet Match<span>.</span></a>
        </div>
        <nav class="navbar">
            <a href="file.php">Home</a>
            <a href="AboutUs.html">About Us</a>
            <a href="Services.html">Services</a>
            <a href="Contact.html">Contacts</a>
            <a href="reviews.php">Reviews</a>
        </nav>
        <div class="icons">
            <a href="favorites.php" class="fas fa-heart"></a>
            <a href="adoptions.php" class="fas fa-dog"></a>
            <a href="my_info.php" class="fas fa-user"></a>
        </div>
    </header>

    <section class="favorites" id="favorites">
        <h1 class="heading"><span>My Favorite</span> Animals</h1>
        <div class="adoptions-container">
            <?php if ($favorites_exist): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="adoption-card">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Animal Image">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><strong>Specie:</strong> <?php echo htmlspecialchars($row['species']); ?></p>
                        <p><strong>Rasă:</strong> <?php echo htmlspecialchars($row['breed']); ?></p>
                        <p><strong>Vârstă:</strong> <?php echo htmlspecialchars($row['age']); ?> ani</p>
                        <p><strong>Sex:</strong> <?php echo htmlspecialchars($row['sex']); ?></p>
                        <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="no-favorites">Nu aveți animale favorite.</p>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
