<?php
session_start();
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $degree = $_POST["degree"];
    $year = $_POST["year"];

    // Check if email already exists
    $checkEmail = "SELECT id FROM students WHERE email=?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "❌ This email is already registered!";
    } else {
        // Insert new student data
        $sql = "INSERT INTO students (name, email, password, degree, graduation_year) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $password, $degree, $year);

        if ($stmt->execute()) {
            $_SESSION["student_email"] = $email;
            header("Location: 08-STUDENT-HOMEPAGE.php");
            exit();
        } else {
            $message = "❌ Signup failed, please try again!";
        }
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Signup - Alumni Networking Platform</title>
    <link rel="stylesheet" href="03-STUDENT-SIGNUP.css">
    <style>
        .error { color: red; font-size: 0.85em; }
        .message { text-align: center; color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Student Signup</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form id="signupForm" method="POST" action="">
            <input type="text" id="name" name="name" placeholder="Full Name" required>
            <small id="nameError" class="error"></small>

            <input type="email" id="email" name="email" placeholder="University Provided Email*" required>
            <small id="emailError" class="error"></small>

            <input type="password" id="password" name="password" placeholder="Password" required>
            <small id="passwordError" class="error"></small>

            <select id="degree" name="degree" required>
                <option value="">Select Degree</option>
                <option value="Software Engineering">Software Engineering</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Business Administration">Business Administration</option>
            </select>
            <small id="degreeError" class="error"></small>

            <select id="year" name="year" required>
                <option value="">Select Graduation Year</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
            </select>
            <small id="yearError" class="error"></small>

            <button type="submit">Signup</button>
        </form>

        <div class="links">
            <a href="02-STUDENT-LOGIN.php">Already have an account? Login</a>
        </div>
    </div>

    <script>
        document.getElementById("signupForm").addEventListener("submit", function(event) {
            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();
            let degree = document.getElementById("degree").value;
            let year = document.getElementById("year").value;

            let nameError = document.getElementById("nameError");
            let emailError = document.getElementById("emailError");
            let passwordError = document.getElementById("passwordError");
            let degreeError = document.getElementById("degreeError");
            let yearError = document.getElementById("yearError");

            nameError.innerText = "";
            emailError.innerText = "";
            passwordError.innerText = "";
            degreeError.innerText = "";
            yearError.innerText = "";

            let isValid = true;

            if (name === "" || !name.includes(" ")) {
                nameError.innerText = "Please provide full name (first and last)";
                isValid = false;
            }

            if (!email.match(/^\S+@\S+\.\S+$/)) {
                emailError.innerText = "Invalid email format";
                isValid = false;
            }

            if (password.length < 8) {
                passwordError.innerText = "Password must be at least 8 characters long";
                isValid = false;
            }

            if (degree === "") {
                degreeError.innerText = "Please select a degree";
                isValid = false;
            }

            if (year === "") {
                yearError.innerText = "Please select a graduation year";
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); // prevent form submission
            }
        });
    </script>
</body>
</html>
