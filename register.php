<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO jobseekers (Username, Password, Email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $email);

    if ($stmt->execute()) 
    {
        echo "<center>Registration Successful! <a href='index.html'>Login Here</a></center>";
    } else {
        echo "<center>Registration Failed. Try Again.</center>";
    }

    $stmt->close();
}

$conn->close();
?>
