<?php
include('db.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Un utilizator cu acest email există deja.";
    } else {
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

       
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.html"); 
            exit();
        } else {
            echo "Eroare: " . $conn->error;
        }
    }

    
    $stmt->close();
}


$conn->close();
?>
