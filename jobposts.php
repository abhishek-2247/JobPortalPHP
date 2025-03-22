<?php
session_start();
include("db.php");


if (!isset($_SESSION['username'])) {
    header("Location: index.html");
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
    <title>Job Listings</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #007bff;
            color: white;
            padding: 15px 20px;
        }

        .header h2 {
            margin: 0;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .search-container {
            text-align: center;
            margin: 20px 0;
        }

        .search-box {
            width: 50%;
            padding: 10px;
            font-size: 1rem;
            border: 2px solid #007bff;
            border-radius: 5px;
            outline: none;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .job-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-left: 5px solid #007bff;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease-in-out;
        }

        .job-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }

        .job-title {
            font-size: 1.2rem;
            color: #007bff;
            margin-bottom: 5px;
        }

        .job-company {
            font-weight: bold;
            color: #333;
        }

        .job-location {
            color: #666;
            font-size: 0.9rem;
        }

        .job-description {
            margin-top: 10px;
            color: #444;
            font-size: 0.9rem;
            flex-grow: 1;
        }

        .apply-btn {
            display: inline-block;
            margin-top: 10px;
            background: #28a745;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            text-align: center;
        }

        .apply-btn:hover {
            background: #218838;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            }
            .search-box {
                width: 80%;
            }
        }

        @media (max-width: 480px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script>
        function searchJobs() {
            let input = document.getElementById("search").value.toLowerCase();
            let jobCards = document.getElementsByClassName("job-card");

            for (let i = 0; i < jobCards.length; i++) {
                let title = jobCards[i].querySelector(".job-title").innerText.toLowerCase();
                let company = jobCards[i].querySelector(".job-company").innerText.toLowerCase();
                let location = jobCards[i].querySelector(".job-location").innerText.toLowerCase();

                if (title.includes(input) || company.includes(input) || location.includes(input)) {
                    jobCards[i].style.display = "block";
                } else {
                    jobCards[i].style.display = "none";
                }
            }
        }
    </script>
</head>
<body>

    <div class="header">
        <h2>Available Job Listings</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="search-container">
        <input type="text" id="search" class="search-box" placeholder="Search jobs by title, company, or location..." onkeyup="searchJobs()">
    </div>

    <div class="container">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="job-card">
                <h3 class="job-title"><?php echo $row["title"]; ?></h3>
                <p class="job-company"><?php echo $row["company"]; ?></p>
                <p class="job-location"><?php echo $row["location"]; ?></p>
                <p class="job-description"><?php echo substr($row["description"], 0, 100); ?>...</p>
                <a href="apply.php?job_id=<?php echo $row['id']; ?>" class="apply-btn">Apply Now</a>
            </div>
        <?php } ?>
    </div>

</body>
</html>

<?php
$conn->close();
?>
