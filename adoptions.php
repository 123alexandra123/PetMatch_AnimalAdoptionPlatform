<?php
session_start();

include('db.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$username = $_SESSION['username'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['favorite_id'])) {
    $animal_id = intval($_POST['favorite_id']);

   
    $query = $conn->prepare("SELECT favorites FROM users WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();
    $row = $result->fetch_assoc();
    $favorites = $row['favorites'] ? explode(",", $row['favorites']) : [];

    
    if (in_array($animal_id, $favorites)) {
        
        $favorites = array_diff($favorites, [$animal_id]);
    } else {
        
        $favorites[] = $animal_id;
    }


    $favorites_str = implode(",", $favorites);
    $query = $conn->prepare("UPDATE users SET favorites = ? WHERE username = ?");
    $query->bind_param("ss", $favorites_str, $username);
    $query->execute();
}


$query = "SELECT * FROM adoptions";
$result = $conn->query($query);


$query = $conn->prepare("SELECT favorites FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result_favorites = $query->get_result();
$row_favorites = $result_favorites->fetch_assoc();
$user_favorites = $row_favorites['favorites'] ? explode(",", $row_favorites['favorites']) : [];
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoptions Page - Pet Match</title>
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

    <section class="adoptions" id="adoptions">
        <h1 class="heading"><span>Adoptions</span> Page</h1>
        <div class="adoptions-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="adoption-card">
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Animal Image">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><strong>Specie:</strong> <?php echo htmlspecialchars($row['species']); ?></p>
                    <p><strong>Rasă:</strong> <?php echo htmlspecialchars($row['breed']); ?></p>
                    <p><strong>Vârstă:</strong> <?php echo htmlspecialchars($row['age']); ?> ani</p>
                    <p><strong>Sex:</strong> <?php echo htmlspecialchars($row['sex']); ?></p>
                    <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
                    <form method="POST" class="favorite-form">
                        <input type="hidden" name="favorite_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="favorite-btn">
                            <i class="fas fa-heart" style="color: <?php echo in_array($row['id'], $user_favorites) ? 'red' : 'gray'; ?>;"></i>
                        </button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</body>
</html>
