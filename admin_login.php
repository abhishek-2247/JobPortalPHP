<?php
session_start();
include("db.php");

$errorMessage = "";

if (isset($_SESSION['admin']) || isset($_COOKIE['admin_login'])) {
    $_SESSION['admin'] = $_COOKIE['admin_login'] ?? $_SESSION['admin'];
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['admin_name'];
    $password = md5($_POST['admin_password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($password == $row['password']) {
            $_SESSION['admin'] = $username;

            setcookie("admin_login", $username, time() + (3 * 60), "/");

            header("Location: admin.php");
            exit();
        } else {
            $errorMessage = "❌ Invalid Password!";
        }
    } else {
        $errorMessage = "❌ Admin not found!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 50px;
        }

        .login-container {
            width: 350px;
            padding: 20px;
            margin: auto;
            background: white;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="admin_login.php" method="POST">
            <input type="text" name="admin_name" placeholder="Admin Username" required>
            <input type="password" name="admin_password" placeholder="Admin Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <?php if (!empty($errorMessage)) { echo "<p class='error'>$errorMessage</p>"; } ?>
    </div>

</body>
</html>
