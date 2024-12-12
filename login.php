<?php
session_start();
include('db.php');  

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);  
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; 

            header("Location: file.php");  
            exit();
        } else {
            echo "Parolă incorectă.";
        }
    } else {
        echo "Utilizatorul nu există.";
    }

    $stmt->close();  

$conn->close(); 
?>
