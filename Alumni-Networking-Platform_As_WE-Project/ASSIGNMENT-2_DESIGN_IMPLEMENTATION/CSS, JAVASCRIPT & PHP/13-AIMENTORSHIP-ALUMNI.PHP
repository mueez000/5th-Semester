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

// Fetch mentorship requests from database
$students = [];
$alumni_id = $_SESSION["alumni_id"] ?? null;

if (!$alumni_id) {
    header("Location: 04-ALUMNI-LOGIN.php");
    exit();
}

$sql = "SELECT students.id, students.name, students.photo 
        FROM students 
        INNER JOIN mentorship_requests ON students.id = mentorship_requests.student_id 
        WHERE mentorship_requests.mentor_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $alumni_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
$stmt->close();
$conn->close();

// Handle accepting mentorship request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["student_id"])) {
    $student_id = $_POST["student_id"];

    $conn = new mysqli("localhost", "root", "", "learnercanvas");

    // Check if request already accepted
    $checkSQL = "SELECT id FROM accepted_mentorships WHERE mentor_id = ? AND student_id = ?";
    $stmt = $conn->prepare($checkSQL);
    $stmt->bind_param("ii", $alumni_id, $student_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('You have already accepted this student’s mentorship request!');</script>";
    } else {
        // Insert accepted mentorship into the database
        $sql = "INSERT INTO accepted_mentorships (mentor_id, student_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $alumni_id, $student_id);
        if ($stmt->execute()) {
            echo "<script>alert('Mentorship request accepted successfully!'); window.location.href='13-AIMENTORSHIP-ALUMNI.php';</script>";
        } else {
            echo "Failed to accept request!";
        }
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Alumni AI Mentorship - LearnerCanvas</title>
    <link rel="stylesheet" href="13-AIMENTORSHIP-ALUMNI.css" />
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
                <li><a href="20-MESSAGE-ALUMNI.php">Messaging</a></li>
                <li><a href="?logout=true">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Alumni AI Mentorship</h2>

        <div class="mentors">
            <?php foreach ($students as $student): ?>
                <div class="student-card">
                    <img src="<?= htmlspecialchars($student['photo']) ?>" alt="<?= htmlspecialchars($student['name']) ?>" />
                    <h3><?= htmlspecialchars($student['name']) ?></h3>
                    <!-- Education removed because column does not exist -->
                    <form method="post">
                        <input type="hidden" name="student_id" value="<?= $student['id'] ?>" />
                        <button type="submit" class="response-btn">Accept Request</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="navigation">
            <button class="nav-btn" onclick="window.location.href='previous-page.html'">Back</button>
            <button class="nav-btn" onclick="window.location.href='next-page.html'">Next</button>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
    </footer>

    <script>
        function logout() {
            window.location.href = "?logout=true";
        }
    </script>
</body>
</html>
