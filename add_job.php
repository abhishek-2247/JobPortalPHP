<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $company = $_POST["company"];
    $location = $_POST["location"];
    $description = $_POST["description"];

    $stmt = $conn->prepare("INSERT INTO jobs (title, company, location, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $company, $location, $description);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error adding job.";
    }

    $stmt->close();
    $conn->close();
}
?>
