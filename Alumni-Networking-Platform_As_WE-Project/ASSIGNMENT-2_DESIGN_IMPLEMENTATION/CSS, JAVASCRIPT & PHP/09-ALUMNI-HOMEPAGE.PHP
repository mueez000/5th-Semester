<?php
session_start();
include 'config.php';

// Logout handler
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: 04-ALUMNI-LOGIN.php");
    exit();
}

$alumni_id = $_SESSION["alumni_id"] ?? null;
$alumni_name = "Alumni";
$profile_completed = 0;

// Fetch alumni data if logged in
if ($alumni_id) {
    $sql = "SELECT name, profile_completed FROM alumni WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $alumni_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $alumni_name = $row["name"];
        $profile_completed = $row["profile_completed"];
    }
    $stmt->close();
} else {
    header("Location: 04-ALUMNI-LOGIN.php");
    exit();
}

// Redirection logic with validation
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Basic whitelist check for pages to prevent open redirect
    $allowed_pages = [
        '13-AIMENTORSHIP-ALUMNI.php',
        '15-LEARN&EARN-ALUMNI.php',
        '16-FUNDRAIZING.php',
        '17-FEEDBACK.php'
    ];

    if (!$profile_completed) {
        header("Location: 11-PROFILE-CREATION.php");
        exit();
    } elseif (in_array($page, $allowed_pages)) {
        header("Location: $page");
        exit();
    } else {
        // Unknown page, redirect home
        header("Location: 09-ALUMNI-HOMEPAGE.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Alumni Home - Alumni Networking Platform</title>
<link rel="stylesheet" href="09-ALUMNI-HOMEPAGE.css" />
</head>
<body>
<header>
    <div class="logo">
        <img src="logo.png" alt="LearnerCanvas Logo" />
        <h1>LearnerCanvas</h1>
    </div>
    <nav>
        <ul>
            <li><a href="09-ALUMNI-HOMEPAGE.php">Home</a></li>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="welcome">
    <h2>Welcome back, <?php echo htmlspecialchars($alumni_name); ?>!</h2>
</div>

<main>
    <section>
        <h2>Connect, Grow, Give Back</h2><br />
        <div class="cards">
            <article class="card">
                <img src="mentorship.jpg" alt="Mentorship" />
                <h3>AI Mentorship</h3>
                <p>Guide the next generation with AI-powered mentor matching. Connect with students who need your expertise and shape future leaders.</p>
                <button onclick="redirectPage('13-AIMENTORSHIP-ALUMNI.php')">Explore</button>
            </article>
            <article class="card">
                <img src="learn-earn.jpg" alt="Learn and Earn" />
                <h3>Learn & Earn</h3>
                <p>Share your expertise, collaborate on real-world projects, and give back to the community—because learning never stops.</p>
                <button onclick="redirectPage('15-LEARN&EARN-ALUMNI.php')">Start</button>
            </article>
            <article class="card">
                <img src="fundraising.jpg" alt="Fundraising" />
                <h3>Fundraising</h3>
                <p>Empower innovation by supporting student-led startups. Invest, collaborate, and help shape the next big ideas.</p>
                <button onclick="redirectPage('16-FUNDRAIZING.php')">Support</button>
            </article>
            <article class="card">
                <img src="feedback.jpg" alt="Feedback" />
                <h3>Feedback</h3>
                <p>Provide valuable insights to help universities align education with industry needs.</p>
                <button onclick="redirectPage('17-FEEDBACK.php')">Give Feedback</button>
            </article>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
</footer>

<script>
function redirectPage(page) {
    window.location.href = `?page=${encodeURIComponent(page)}`;
}
</script>
</body>
</html>
