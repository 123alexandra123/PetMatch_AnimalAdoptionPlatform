<?php
session_start();
include('db.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids'];

    if (!empty($delete_ids)) {
        $placeholders = implode(',', array_fill(0, count($delete_ids), '?'));
        $query = $conn->prepare("DELETE FROM adoptions WHERE id IN ($placeholders)");
        $query->bind_param(str_repeat('i', count($delete_ids)), ...$delete_ids);
        $query->execute();

       
        $success_message = "Toate animalele selectate au fost șterse cu succes!";
    }
}

$query = "SELECT * FROM adoptions";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pets Paradise</title>
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


    <section class="animal-management" id="animal-management">
        <h1 class="heading"><span>Delete</span> Pets</h1>
        <form method="POST" class="delete-form-new">
            <div class="animal-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="animal-card">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Animal Image">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><strong>Specie:</strong> <?php echo htmlspecialchars($row['species']); ?></p>
                        <p><strong>Rasă:</strong> <?php echo htmlspecialchars($row['breed']); ?></p>
                        <p><strong>Vârstă:</strong> <?php echo htmlspecialchars($row['age']); ?> ani</p>
                        <p><strong>Sex:</strong> <?php echo htmlspecialchars($row['sex']); ?></p>
                        <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
                        <input type="checkbox" name="delete_ids[]" value="<?php echo $row['id']; ?>" class="delete-checkbox-new">
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="delete-button-container-new">
                <button type="submit" class="btn">Delete Selected</button>
            </div>
        </form>
    </section>

    
    <?php if (!empty($success_message)): ?>
        <div class="popup-message" id="popupMessage">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <script>
        
        const popupMessage = document.getElementById('popupMessage');
        if (popupMessage) {
            popupMessage.style.display = 'block';
            setTimeout(() => {
                popupMessage.style.display = 'none';
            }, 3000); 
        }
    </script>
</body>
</html>
