<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id, password FROM admins WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["admin_id"] = $row["id"];
            header("Location: 10-ADMIN-HOMEPAGE.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Invalid email!";
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
    <title>Admin Login - Alumni Networking Platform</title>
    <link rel="stylesheet" href="06-ADMIN-LOGIN.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form id="loginForm" action="06-ADMIN-LOGIN.PHP" method="POST">
            <input type="email" id="email" name="email" placeholder="Admin Email*" required>
            <small id="emailError" class="error"></small>

            <input type="password" id="password" name="password" placeholder="Password" required>
            <small id="passwordError" class="error"></small>

            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();
            let emailError = document.getElementById("emailError");
            let passwordError = document.getElementById("passwordError");

            // Clear previous error messages
            emailError.innerText = "";
            passwordError.innerText = "";

            let isValid = true;

            // Validate email format
            if (!email.match(/^\S+@\S+\.\S+$/)) {
                emailError.innerText = "Invalid email format";
                isValid = false;
            }

            // Validate password length
            if (password.length < 8) {
                passwordError.innerText = "Password must be at least 8 characters long";
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Redirect to Admin Homepage after successful login
            setTimeout(() => {
                window.location.href = "10-ADMIN-HOMEPAGE.php";
            }, 500);
        });
    </script>
</body>
</html>