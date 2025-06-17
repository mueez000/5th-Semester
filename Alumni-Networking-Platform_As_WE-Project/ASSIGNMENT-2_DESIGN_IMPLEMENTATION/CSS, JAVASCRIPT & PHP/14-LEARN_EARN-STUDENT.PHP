<?php
session_start();
include 'config.php';

// Logout handler
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: 02-STUDENT-LOGIN.php");
    exit();
}

// Fetch jobs and freelance projects from database
$jobs = [];
$freelance_projects = [];

$sql_jobs = "SELECT id, title, company, description FROM jobs";
$result_jobs = $conn->query($sql_jobs);

while ($row = $result_jobs->fetch_assoc()) {
    $jobs[] = $row;
}

$sql_freelance = "SELECT id, title, company, description FROM freelance_projects";
$result_freelance = $conn->query($sql_freelance);

while ($row = $result_freelance->fetch_assoc()) {
    $freelance_projects[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Learn & Earn - Student Job Page</title>
    <link rel="stylesheet" href="14-LEARN_EARN-STUDENT.css" />
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="LearnerCanvas Logo" />
            <h1>LearnerCanvas</h1>
        </div>
        <nav>
            <ul>
                <li><a href="08-STUDENT-HOMEPAGE.php">Home</a></li>
                <li><a href="18-SUCCESSSTORIES.php">Success Stories</a></li>
                <li><a href="?logout=true">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Learn & Earn - Student Job Opportunities</h2>

        <div class="toggle-container">
            <span class="toggle-label">Jobs</span>
            <label class="toggle-switch">
                <input type="checkbox" id="toggleSwitch" />
                <span class="slider"></span>
            </label>
            <span class="toggle-label">Freelance</span>
        </div>

        <div id="jobList" class="job-list">
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-details">
                        <h3><?= htmlspecialchars($job['title']) ?></h3>
                        <p>Company: <?= htmlspecialchars($job['company']) ?></p>
                        <p><?= htmlspecialchars($job['description']) ?></p>
                        <form method="post">
                            <input type="hidden" name="job_id" value="<?= $job['id'] ?>" />
                            <button type="submit" class="apply-btn">Apply</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="freelanceList" class="freelance-list" style="display:none;">
            <?php foreach ($freelance_projects as $project): ?>
                <div class="job-card">
                    <div class="job-details">
                        <h3><?= htmlspecialchars($project['title']) ?></h3>
                        <p>Company: <?= htmlspecialchars($project['company']) ?></p>
                        <p><?= htmlspecialchars($project['description']) ?></p>
                        <form method="post">
                            <input type="hidden" name="job_id" value="<?= $project['id'] ?>" />
                            <button type="submit" class="apply-btn">Apply</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="navigation">
            <button class="nav-btn">Back</button>
            <button class="nav-btn">Next</button>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggleSwitch = document.getElementById("toggleSwitch");
            const jobList = document.getElementById("jobList");
            const freelanceList = document.getElementById("freelanceList");

            function toggleView() {
                if (toggleSwitch.checked) {
                    jobList.style.display = "none";
                    freelanceList.style.display = "block";
                } else {
                    jobList.style.display = "block";
                    freelanceList.style.display = "none";
                }
            }

            toggleSwitch.addEventListener("change", toggleView);
            toggleView();
        });
    </script>
</body>
</html>
