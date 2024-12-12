<?php
session_start();
include('db.php');


if (!isset($_SESSION['username']) || !isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


$username = $_SESSION['username'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review = trim($conn->real_escape_string($_POST['review'] ?? ''));
    $rating = (int)($_POST['rating'] ?? 0);

    if (!empty($review) && $rating > 0) {
        $stmt = $conn->prepare("INSERT INTO reviews (username, review, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $review, $rating);
        $stmt->execute();
        $stmt->close();
        header("Location: reviews.php");
        exit();
    } else {
        echo "Review-ul și rating-ul nu pot fi goale.";
    }
}


$result = $conn->query("SELECT username, review, rating, created_at FROM reviews ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - Pet Match</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <input type="checkbox" id="toggler">
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
        </nav>
        <div class="icons">
            <a href="favorites.php" class="fas fa-heart"></a>
            <a href="adoptions.php" class="fas fa-dog"></a>
            <a href="my_info.php" class="fas fa-user"></a>
        </div>
    </header>

    <section class="reviews" id="reviews">
        <h1 class="heading"><span>Leave</span> a Review</h1>
        <div class="reviews-container">
            <form action="reviews.php" method="POST">
                <textarea name="review" rows="5" placeholder="Write your review here..." required></textarea>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars">☆</label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars">☆</label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars">☆</label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars">☆</label>
                    <input type="radio" id="star1" name="rating" value="1" checked><label for="star1" title="1 star">☆</label>
                </div>
                <button type="submit" class="btn">Submit Review</button>
            </form>
        </div>

        <h1 class="heading"><span>All</span> Reviews</h1>
        <div class="reviews-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="review-box">
                    <div class="review-header">
                        <h3 class="review-username"><?php echo htmlspecialchars($row['username']); ?></h3>
                        <div class="review-meta">
                            <span class="review-date"><?php echo date("d M Y, H:i", strtotime($row['created_at'])); ?></span>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="fa fa-star <?php echo $i <= $row['rating'] ? 'checked' : ''; ?>"></span>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <div class="review-body">
                        <p class="review-content"><?php echo nl2br(htmlspecialchars($row['review'])); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>
</body>
</html>
<?php $conn->close(); ?>
