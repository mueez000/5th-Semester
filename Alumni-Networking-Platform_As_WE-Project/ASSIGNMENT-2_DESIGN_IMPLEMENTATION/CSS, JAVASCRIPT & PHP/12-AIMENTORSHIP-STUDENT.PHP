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

// Fetch mentors from the database
$mentors = [];
$sql = "SELECT id, name, photo, job_title, company FROM mentors";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $mentors[] = $row;
}
$conn->close();

// Handle mentorship request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mentor_id"])) {
    $mentor_id = $_POST["mentor_id"];
    $student_id = $_SESSION["student_id"] ?? null;

    if (!$student_id) {
        header("Location: 02-STUDENT-LOGIN.php");
        exit();
    }

    $conn = new mysqli("localhost", "root", "", "learnercanvas");

    // Check if request already exists
    $checkSQL = "SELECT id FROM mentorship_requests WHERE student_id = ? AND mentor_id = ?";
    $stmt = $conn->prepare($checkSQL);
    $stmt->bind_param("ii", $student_id, $mentor_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('You have already sent a mentorship request to this mentor!');</script>";
    } else {
        // Insert mentorship request into the database
        $sql = "INSERT INTO mentorship_requests (student_id, mentor_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $student_id, $mentor_id);
        if ($stmt->execute()) {
            echo "<script>alert('Mentorship request sent successfully!'); window.location.href='12-AIMENTORSHIP-STUDENT.php';</script>";
        } else {
            echo "Failed to send request!";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Mentorship - LearnerCanvas</title>
    <link rel="stylesheet" href="12-AIMENTORSHIP-STUDENT.css">
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

    <main>
        <h2>AI Mentorship</h2>
        <h3>Mentorship that best matches you</h3>

        <div class="mentors">
            <?php foreach ($mentors as $mentor): ?>
                <div class="mentor-card">
                    <img src="<?= htmlspecialchars($mentor['photo']) ?>" alt="<?= htmlspecialchars($mentor['name']) ?>">
                    <h3><?= htmlspecialchars($mentor['name']) ?></h3>
                    <p><?= htmlspecialchars($mentor['job_title']) ?></p>
                    <p><?= htmlspecialchars($mentor['company']) ?></p>
                    <form method="post">
                        <input type="hidden" name="mentor_id" value="<?= $mentor['id'] ?>">
                        <button type="submit" class="message-btn">Request Mentorship</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="next-btn" onclick="window.location.href='next-mentors.html'">Next</button>
        <button class="next-btn" onclick="window.location.href='mentorship.html'">Back</button>
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