<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION["student_id"])) {
    header("Location: 02-STUDENT-LOGIN.php");
    exit();
}

$user_id = $_SESSION["student_id"];
$user_name = "Student";
$profile_completed = 0;

// Fetch student name and profile status
$sql = "SELECT name, profile_completed FROM students WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $user_name = $row["name"];
    $profile_completed = $row["profile_completed"];
}
$stmt->close();

// Logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: 02-STUDENT-LOGIN.php");
    exit();
}

// Redirection based on profile completion
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    if ($profile_completed == 0) {
        // If profile is not completed, force user to complete it
        header("Location: 11-PROFILE-CREATION.php");
    } else {
        header("Location: $page");
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home - Alumni Networking Platform</title>
    <link rel="stylesheet" href="08-STUDENT-HOMEPAGE.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="LearnerCanvas Logo">
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

    <div class="welcome">
        <h2>Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h2>
    </div>

    <main>
        <section>
            <h2>Explore Your Opportunities</h2>
            <br>
            <div class="cards">
                <article class="card">
                    <img src="mentorship.jpg" alt="Mentorship">
                    <h3>AI Mentorship</h3>
                    <p>Get matched with experienced mentors using AI-powered recommendations. Learn, grow, and succeed with guidance.</p>
                    <button onclick="redirectPage('12-AIMENTORSHIP-STUDENT.php')">Explore</button>
                </article>
                <article class="card">
                    <img src="learn-earn.jpg" alt="Learn and Earn">
                    <h3>Learn & Earn</h3>
                    <p>Work on real-world freelance projects, enhance your skills, and earn while you learn. Start your journey today!</p>
                    <button onclick="redirectPage('14-LEARN_EARN-STUDENT.php')">Start</button>
                </article>
                <article class="card">
                    <img src="fundraising.jpg" alt="Fundraising">
                    <h3>Fundraising</h3>
                    <p>Empower innovation by supporting student-led startups. Invest, collaborate, and help shape the next big ideas.</p>
                    <button onclick="redirectPage('16-FUNDRAIZING.php')">Support</button>
                </article>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
    </footer>

    <script>
        function redirectPage(page) {
            window.location.href = `?page=${page}`;
        }
    </script>
</body>
</html>