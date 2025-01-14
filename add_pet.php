<?php
session_start();
include('db.php');


$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$species = isset($_SESSION['species']) ? $_SESSION['species'] : '';
$breed = isset($_SESSION['breed']) ? $_SESSION['breed'] : '';
$age = isset($_SESSION['age']) ? $_SESSION['age'] : '';
$sex = isset($_SESSION['sex']) ? $_SESSION['sex'] : '';
$description = isset($_SESSION['description']) ? $_SESSION['description'] : '';
$image_path = isset($_SESSION['image_path']) ? $_SESSION['image_path'] : 'images/default.png';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';

unset($_SESSION['success_message']); 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_form'])) {
    unset($_SESSION['name'], $_SESSION['species'], $_SESSION['breed'], $_SESSION['age'], $_SESSION['sex'], $_SESSION['description']);
    $_SESSION['image_path'] = 'images/default.png'; 
    header("Location: add_pet.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_photo'])) {
    if (isset($_FILES['new_photo']) && $_FILES['new_photo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = basename($_FILES["new_photo"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name;

        if (move_uploaded_file($_FILES["new_photo"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
            $_SESSION['image_path'] = $image_path;
            $_SESSION['success_message'] = 'Imaginea a fost încărcată cu succes!';
        } else {
            $_SESSION['success_message'] = 'Eroare la încărcarea imaginii.';
        }
        header("Location: add_pet.php");
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_pet'])) {
    $_SESSION['name'] = $name = $_POST['name'];
    $_SESSION['species'] = $species = $_POST['species'];
    $_SESSION['breed'] = $breed = $_POST['breed'];
    $_SESSION['age'] = $age = $_POST['age'];
    $_SESSION['sex'] = $sex = $_POST['sex'];
    $_SESSION['description'] = $description = $_POST['description'];

    $image_path = !empty($_SESSION['image_path']) ? $_SESSION['image_path'] : 'images/default.png';

    $sql = "INSERT INTO adoptions (name, species, breed, age, sex, description, image_path)
            VALUES ('$name', '$species', '$breed', '$age', '$sex', '$description', '$image_path')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = 'Animalul a fost adăugat cu succes!';
        unset($_SESSION['name'], $_SESSION['species'], $_SESSION['breed'], $_SESSION['age'], $_SESSION['sex'], $_SESSION['description'], $_SESSION['image_path']);
        header("Location: add_pet.php");
        exit();
    } else {
        $_SESSION['success_message'] = "Eroare la adăugarea animalului: {$conn->error}";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pet - Pet Match</title>
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
            <a href="reviews.php">Reviews</a>
        </nav>
        <div class="icons">
            <a href="favorites.php" class="fas fa-heart"></a>
            <a href="adoptions.php" class="fas fa-dog"></a>
            <a href="my_info.php" class="fas fa-user"></a>
        </div>
    </header>

    <section class="add_pet" id="add_pet">
        <h1 class="heading"><span>Add</span> a New Pet</h1>
        <div class="add-pet-container">
            <div class="pet-form">
                <form action="add_pet.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Enter pet name" required>
                    </div>
                    <div class="form-group">
                        <label for="species">Species:</label>
                        <input type="text" name="species" id="species" value="<?php echo htmlspecialchars($species); ?>" placeholder="Enter pet species (e.g., Dog, Cat)" required>
                    </div>
                    <div class="form-group">
                        <label for="breed">Breed:</label>
                        <input type="text" name="breed" id="breed" value="<?php echo htmlspecialchars($breed); ?>" placeholder="Enter pet breed" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" name="age" id="age" value="<?php echo htmlspecialchars($age); ?>" placeholder="Enter pet age" required>
                    </div>
                    <div class="form-group">
                        <label for="sex">Sex:</label>
                        <select name="sex" id="sex" required>
                            <option value="Male" <?php echo ($sex === 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($sex === 'Female') ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" placeholder="Enter pet description" required><?php echo htmlspecialchars($description); ?></textarea>
                    </div>
                    <button type="submit" name="add_pet" class="btn">Add Pet</button>
                </form>

                <form action="add_pet.php" method="POST" style="margin-top: 10px;">
                    <button type="submit" name="reset_form" class="btn">Reset Form</button>
                </form>
            </div>

            <div class="pet-image">
                <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Pet Image">
                <form method="POST" enctype="multipart/form-data" class="image-form">
                    <input type="file" name="new_photo" accept="image/*" required>
                    <button type="submit" name="upload_photo" class="btn">Upload Image</button>
                </form>
            </div>
        </div>
    </section>

    <div class="popup-message" id="popupMessage">
        <?php echo htmlspecialchars($success_message); ?>
    </div>

    <script>
        const popupMessage = document.getElementById('popupMessage');
        if (popupMessage.textContent.trim() !== '') {
            popupMessage.style.display = 'block';
            setTimeout(() => {
                popupMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
