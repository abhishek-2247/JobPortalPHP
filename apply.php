<?php
session_start();
include("db.php");

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $username = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO applications (username, job_id, applied_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("si", $username, $job_id);

    if ($stmt->execute()) {
        $message = "🎉 Application submitted successfully!";
        $success = true;
    } else {
        $message = "⚠️ Error applying for job.";
        $success = false;
    }

    $stmt->close();
} else {
    $message = "❌ Invalid job ID.";
    $success = false;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 50px;
        }

        .message-box {
            width: 50%;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            font-size: 1.2rem;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .success {
            background: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            font-size: 1rem;
            text-decoration: none;
            border-radius: 5px;
            color: white;
        }

        .btn-home {
            background: #007bff;
        }

        .btn-home:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="message-box <?php echo $success ? 'success' : 'error'; ?>">
        <p><?php echo $message; ?></p>
    </div>

    <a href="jobposts.php" class="btn btn-home">🔙 Back to Job Listings</a>

</body>
</html>
