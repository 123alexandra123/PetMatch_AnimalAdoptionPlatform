<?php
session_start();  

if (isset($_SESSION['user_id'])) {
    
    header("Location: file.php");
    exit();
} else {
   
    header("Location: login.html");
    exit();
}
?>
