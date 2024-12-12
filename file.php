<?php
session_start();

$isAdmin = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');

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

    <section class="home" id="home">
        <div class="content">
            <h3>Meet Your Best Friend</h3>
            <span>Loving and Loyal animals</span>
            <p>At Pet Match, we're dedicated to bringing together loyal and loving animals with the perfect families.
                We promote responsible adoption ensuring every animal finds a happy and caring home. 
                Join us in finding your perfect companion at Pet Match!</p><br>
            <a href="adoptions.php" class="btn">Adopt now</a><br>

        
            <?php if ($isAdmin): ?>
                <a href="add_pet.php" class="btn">Add Pet</a><br>
                <a href="delete_pet.php" class="btn">Delete Pet</a>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>
