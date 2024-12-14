<?php
session_start();

include('db.php'); 

if (!isset($_SESSION['username']) || !isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header("Location: login.php"); 
    exit();
}

$username = $_SESSION['username'];
$email = $_SESSION['email'];
$role = $_SESSION['role'];


$query = $conn->prepare("SELECT profile_picture FROM users WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();
$profile_picture = $row['profile_picture'] ?? "images/default.png";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['upload_photo'])) {
        if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $file_name = basename($_FILES["new_photo"]["name"]);
            $target_file = $target_dir . uniqid() . "_" . $file_name;

            if (move_uploaded_file($_FILES["new_photo"]["tmp_name"], $target_file)) {
                $query = $conn->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
                $query->bind_param("ss", $target_file, $username);
                $query->execute();
                $profile_picture = $target_file;
            }
        }
    } elseif (isset($_POST['delete_photo'])) {
        $default_picture = "images/default.png";
        $query = $conn->prepare("UPDATE users SET profile_picture = ? WHERE username = ?");
        $query->bind_param("ss", $default_picture, $username);
        $query->execute();
        $profile_picture = $default_picture;
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Pet Match</title>
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

    <section class="my-account" id="my-account">
        <h1 class="heading"><span>My</span> Account</h1>
        <div class="account-container">
            <div class="account-details">
                <div class="account-info">
                    <h3>User Information</h3>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Role:</strong> <?php echo htmlspecialchars($role); ?></p>
                </div>
                <div class="logout-container">
                    <a href="logout.php" class="btn">Logout</a>
                </div>
            </div>
            <div class="account-image">
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" onerror="this.src='images/default.png';">

                <form method="POST" enctype="multipart/form-data" class="image-form">
                    <input type="file" name="new_photo" accept="image/*">
                    <button type="submit" name="upload_photo" class="btn">Upload Photo</button>
                    <button type="submit" name="delete_photo" class="btn">Delete Photo</button>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
