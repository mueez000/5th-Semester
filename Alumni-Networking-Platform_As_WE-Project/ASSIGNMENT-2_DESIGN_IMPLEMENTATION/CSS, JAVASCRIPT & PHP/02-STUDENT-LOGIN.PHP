<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = strtolower(trim($_POST["email"]));
    $password = trim($_POST["password"]);

    $sql = "SELECT id, password FROM students WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["student_id"] = $row["id"];
            header("Location: 08-STUDENT-HOMEPAGE.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Invalid email!";
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
    <title>Student Login - Alumni Networking Platform</title>
    <link rel="stylesheet" href="02-STUDENT-LOGIN.css">
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message" style="color: red; margin-bottom: 10px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="02-STUDENT-LOGIN.php">
            <input type="email" id="email" name="email" placeholder="University Provided Email*" required>
            <small id="emailError" class="error"></small>

            <input type="password" id="password" name="password" placeholder="Password" required>
            <small id="passwordError" class="error"></small>

            <button type="submit">Login</button>
        </form>

        <div class="links">
            <a href="03-STUDENT-SIGNUP.php">Signup for LearnerCanvas</a>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();
            let emailError = document.getElementById("emailError");
            let passwordError = document.getElementById("passwordError");

            emailError.innerText = "";
            passwordError.innerText = "";

            let isValid = true;

            if (!email.match(/^\S+@\S+\.\S+$/)) {
                emailError.innerText = "Invalid email format";
                isValid = false;
            }

            if (password.length < 8) {
                passwordError.innerText = "Password must be at least 8 characters long";
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); // Prevent submit only if validation fails
            }
        });
    </script>
</body>
</html>
