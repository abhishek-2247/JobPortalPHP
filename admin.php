<?php
session_start();
include("db.php");

if (!isset($_SESSION['admin']) && isset($_COOKIE['admin_login'])) {
    echo "<center> From Cookie ADMIN: ".$_COOKIE['admin_login']."</center>";
    $_SESSION['admin'] = $_COOKIE['admin_login'];
}

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit();
}

if (isset($_GET['delete'])) {
    $job_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php");
    exit();
}

$sql = "SELECT * FROM jobs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .logout-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: rgb(3, 110, 114);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s ease;
        }

        .logout-btn:hover {
            background: rgb(40, 20, 223);
            transform: scale(1.05);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .nav button {
            background: #007bff;
            border: none;
            color: white;
            font-size: 18px;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .nav button:nth-child(2) {
            background: #28a745;
        }

        .nav button:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .nav button.active {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
        }

        .section {
            display: none;
            padding: 20px;
        }

        .section.active {
            display: block;
        }

        .job-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .job-card {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .submit-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

    <div class="container">
        <button class="logout-btn" onclick="logout()">Logout</button>

        <h2>Admin Panel - Job Management</h2>

        <div class="nav">
            <button onclick="showSection('manageJobs')" id="btnManage" class="active">Manage Jobs</button>
            <button onclick="showSection('addJob')" id="btnAdd">Add New Job</button>
        </div>

        <div id="manageJobs" class="section active">
            <h3>Manage Jobs (Delete Jobs)</h3>
            <div class="job-list">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="job-card">
                        <div>
                            <strong><?php echo $row["title"]; ?></strong>
                            <p><?php echo $row["company"]; ?> - <?php echo $row["location"]; ?></p>
                        </div>
                        <button class="delete-btn" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div id="addJob" class="section">
            <h3>Add New Job</h3>
            <form action="add_job.php" method="POST">
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Company:</label>
                    <input type="text" name="company" required>
                </div>
                <div class="form-group">
                    <label>Location:</label>
                    <input type="text" name="location" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="3" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Add Job</button>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete(jobId) {
            if (confirm("Are you sure you want to delete this job?")) {
                window.location.href = "admin.php?delete=" + jobId;
            }
        }

        function showSection(section) {
            document.getElementById("manageJobs").classList.remove("active");
            document.getElementById("addJob").classList.remove("active");
            document.getElementById(section).classList.add("active");

            document.getElementById("btnManage").classList.remove("active");
            document.getElementById("btnAdd").classList.remove("active");

            if (section === "manageJobs") {
                document.getElementById("btnManage").classList.add("active");
            } else {
                document.getElementById("btnAdd").classList.add("active");
            }
        }

        function logout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>

</body>
</html>

<?php $conn->close(); ?>
