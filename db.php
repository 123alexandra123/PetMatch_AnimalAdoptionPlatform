<?php

$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "db";  


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexiunea la baza de date a eșuat: " . $conn->connect_error);
}


if (mysqli_connect_errno()) {
    die("Conexiunea a eșuat: " . mysqli_connect_error());
}
?>
