<?php
session_start();
include 'config.php';

// Logout handler
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: 04-ALUMNI-LOGIN.php"); // Redirect to login page
    exit();
}

// Handle feedback submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $satisfaction = $_POST["satisfaction"];
    $education = $_POST["education"];
    $faculty = $_POST["faculty"];
    $facilities = $_POST["facilities"];
    $networking = $_POST["networking"];
    $career = $_POST["career"];
    $alumni = $_POST["alumni"];
    $improvements = $_POST["improvements"];
    $industry_support = $_POST["industry-support"];
    $comments = $_POST["comments"];
    $alumni_id = $_SESSION["alumni_id"] ?? null;

    if (!$alumni_id) {
        header("Location: 04-ALUMNI-LOGIN.php"); // If session expired, send to login
        exit();
    }

    $sql = "INSERT INTO feedback (alumni_id, satisfaction, education, faculty, facilities, networking, career, alumni, improvements, industry_support, comments)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssssss", $alumni_id, $satisfaction, $education, $faculty, $facilities, $networking, $career, $alumni, $improvements, $industry_support, $comments);

    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href='17-FEEDBACK.html';</script>";
    } else {
        echo "<script>alert('Failed to submit feedback. Please try again!');</script>";
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
    <title>Feedback - LearnerCanvas</title>
    <link rel="stylesheet" href="17-FEEDBACK.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="LearnerCanvas Logo">
            <h1>LearnerCanvas</h1>
        </div>
        <nav>
            <ul>
                <li><a href="09-ALUMNI-HOMEPAGE.php">Home</a></li>
                <li><a href="#" onclick="logout()">Logout</a></li>
            </ul>
        </nav>
    </header>

     <main>
        <h2>University Experience Feedback</h2>

        <form class="feedback-form" onsubmit="return validateForm()">
            <label for="satisfaction">Overall Satisfaction:</label>
            <select id="satisfaction">
                <option value="">Select Rating</option>
                <option>Poor</option>
                <option>Average</option>
                <option>Good</option>
                <option>Excellent</option>
            </select>

            <label for="education">Quality of Education:</label>
            <textarea id="education" placeholder="Were courses relevant to industry needs?" minlength="20"></textarea>

            <label for="faculty">Faculty & Mentorship:</label>
            <textarea id="faculty" placeholder="Were professors supportive & knowledgeable?" minlength="20"></textarea>

            <label for="facilities">Campus Facilities & Resources:</label>
            <select id="facilities">
                <option value="">Select Rating</option>
                <option>Poor</option>
                <option>Average</option>
                <option>Good</option>
                <option>Excellent</option>
            </select>

            <label for="networking">Extracurricular & Networking Opportunities:</label>
            <textarea id="networking" placeholder="Clubs, internships, industry collaborations" minlength="20"></textarea>

            <label for="career">Career Preparation:</label>
            <textarea id="career" placeholder="Job readiness, soft skills, internships" minlength="20"></textarea>

            <label for="alumni">Alumni Networking Programs:</label>
            <textarea id="alumni" placeholder="Suggestions for alumni events or programs" minlength="20"></textarea>

            <label for="improvements">University Improvements:</label>
            <textarea id="improvements" placeholder="Curriculum updates, career services, tech resources, etc." minlength="20"></textarea>

            <label for="industry-support">Industry Partnerships & Job Placement Support:</label>
            <textarea id="industry-support" placeholder="Feedback on industry collaborations" minlength="20"></textarea>

            <label for="comments">Additional Comments:</label>
            <textarea id="comments" placeholder="Any other suggestions or concerns?"></textarea>

            <button type="submit">Submit Feedback</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
    </footer>

    <script>
    function validateForm() {
        clearErrors();
        let isValid = true;

        // Validate required selects
        const requiredSelects = ['satisfaction', 'facilities'];
        requiredSelects.forEach(id => {
            const field = document.getElementById(id);
            if (field.value === "") {
                showError(field, 'This field is required');
                isValid = false;
            }
        });

        // Validate textareas with minimum length
        const requiredTextareas = [
            'education', 'faculty', 'networking',
            'career', 'alumni', 'improvements', 'industry-support'
        ];

        requiredTextareas.forEach(id => {
            const field = document.getElementById(id);
            if (field.value.trim().length < 20) {
                showError(field, 'Please provide at least 20 characters of feedback');
                isValid = false;
            }
        });

        if (isValid) {
            let confirmSubmission = confirm("Are you sure you want to submit your feedback?");
            if (!confirmSubmission) return false;

            alert("Thank you for your feedback! ✅");
            document.querySelector('.feedback-form').reset();
        }
        return false; // Prevent form submission for demonstration
    }

    function showError(field, message) {
        const error = document.createElement('div');
        error.style.color = 'red';
        error.style.fontSize = '0.8em';
        error.style.marginTop = '-10px';
        error.style.marginBottom = '10px';
        error.textContent = message;
        field.parentNode.insertBefore(error, field.nextSibling);
    }

    function clearErrors() {
        document.querySelectorAll('[style="color: red"]').forEach(el => el.remove());
    }

</script>
</body>
</html>