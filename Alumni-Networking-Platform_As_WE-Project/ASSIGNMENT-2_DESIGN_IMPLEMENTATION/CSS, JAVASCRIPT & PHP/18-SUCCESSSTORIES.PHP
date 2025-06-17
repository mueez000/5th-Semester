<?php
session_start();

// Logout handler
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: 01-LANDING-PAGE.php"); // Redirect to landing page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success Stories - LearnerCanvas</title>
    <link rel="stylesheet" href="18-SUCCESSSTORIES.css">
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
                <li><a href="?logout=true">Logout</a></li> <!-- Logout handled via PHP -->
            </ul>
        </nav>
    </header>

    <main>
        <h2>Student Success Stories</h2>

        <div class="stories-container">
            <div class="story-card">
                <img src="success1.jpg" alt="Success Story 1">
                <h3>Sarah - Web Developer</h3>
                <p>Sarah joined our mentorship program and landed her dream job as a front-end developer!</p>
            </div>

            <div class="story-card">
                <img src="success2.jpg" alt="Success Story 2">
                <h3>Ali - AI Researcher</h3>
                <p>Ali's freelance AI project got him noticed by top industry experts and secured a research assistant role!</p>
            </div>

            <div class="story-card">
                <img src="success3.jpg" alt="Success Story 3">
                <h3>Emily - Content Writer</h3>
                <p>After receiving mentorship, Emily started her own successful freelance content writing business.</p>
            </div>

            <div class="story-card">
                <img src="success4.jpg" alt="Success Story 4">
                <h3>John - Data Analyst</h3>
                <p>John mastered data analytics and landed a remote job analyzing business trends for a global company!</p>
            </div>

            <div class="story-card">
                <img src="success5.jpg" alt="Success Story 5">
                <h3>Ayesha - Graphic Designer</h3>
                <p>Ayesha turned her passion for art into a thriving freelance graphic design career.</p>
            </div>

            <div class="story-card">
                <img src="success6.jpg" alt="Success Story 6">
                <h3>David - Mobile App Developer</h3>
                <p>David built his first app through mentorship and now works full-time developing mobile solutions.</p>
            </div>
        </div>

        <div class="navigation">
            <button class="nav-btn">Back</button>
            <button class="nav-btn">Next</button>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
    </footer>
</body>
</html>