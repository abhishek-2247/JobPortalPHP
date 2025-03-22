<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['uname'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM jobseekers WHERE Username = ? AND Password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: jobposts.php"); 
    } else {
        echo "<center>Login Failed</center>";
        echo "<center><br>Go Back and Try Again.......</center>";
    }

    $stmt->close();
    $conn->close();
}
?>
