<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $newPassword = password_hash(trim($_POST["newPassword"]), PASSWORD_DEFAULT); // Secure hashing
    $existingPassword = trim($_POST["existingPassword"]);

    // Verify existing admin password
    $verifyAdmin = "SELECT password FROM admins WHERE email='admin@example.com'"; // Change to actual stored admin email
    $stmt = $conn->prepare($verifyAdmin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (!password_verify($existingPassword, $row["password"])) {
            echo "Existing admin password is incorrect!";
            exit();
        }
    } else {
        echo "Admin verification failed!";
        exit();
    }

    // Check if email already exists
    $checkEmail = "SELECT id FROM admins WHERE email=?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "This email is already registered!";
    } else {
        // Insert new admin data
        $sql = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $newPassword);

        if ($stmt->execute()) {
            echo "Admin signup successful!";
            header("Location: 10-ADMIN-HOMEPAGE.php");
            exit();
        } else {
            echo "Signup failed, please try again!";
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
    <title>Admin Signup - Alumni Networking Platform</title>
    <link rel="stylesheet" href="07-ADMIN-SIGNUP.css">
</head>
<body>
    <div class="signup-container">
        <h2>Admin Signup</h2>
        <form id="signupForm" action="07-ADMIN-SIGNUP.php" method="POST">
            <input type="text" id="name" name="name" placeholder="Full Name" required>
            <small id="nameError" class="error"></small>

            <input type="email" id="email" name="email" placeholder="Admin Email*" required>
            <small id="emailError" class="error"></small>

            <input type="password" id="newPassword" name="newPassword" placeholder="New Admin Password" required>
            <small id="newPasswordError" class="error"></small>

            <input type="password" id="existingPassword" name="existingPassword" placeholder="Existing Admin Password (Verification)" required>
            <small id="existingPasswordError" class="error"></small>

            <button type="submit">Signup</button>
        </form>
    </div>

    <script>
        document.getElementById("signupForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("email").value.trim();
            let newPassword = document.getElementById("newPassword").value.trim();
            let existingPassword = document.getElementById("existingPassword").value.trim();

            let nameError = document.getElementById("nameError");
            let emailError = document.getElementById("emailError");
            let newPasswordError = document.getElementById("newPasswordError");
            let existingPasswordError = document.getElementById("existingPasswordError");

            // Clear previous errors
            nameError.innerText = "";
            emailError.innerText = "";
            newPasswordError.innerText = "";
            existingPasswordError.innerText = "";

            let isValid = true;

            if (name === "" || !name.includes(" ")) {
                nameError.innerText = "Please provide full name (first and last)";
                isValid = false;
            }

            if (!email.match(/^\S+@\S+\.\S+$/)) {
                emailError.innerText = "Invalid email format";
                isValid = false;
            }

            if (newPassword.length < 8) {
                newPasswordError.innerText = "New password must be at least 8 characters long";
                isValid = false;
            }

            if (existingPassword === "") {
                existingPasswordError.innerText = "Existing Admin Password is required";
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            // Simulate successful signup and redirect
            setTimeout(() => {
                window.location.href = "10-ADMIN-HOMEPAGE.php";
            }, 500);
        });
    </script>
</body>
</html>