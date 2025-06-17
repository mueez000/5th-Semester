<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $jobTitle = $_POST["jobTitle"];
    $company = $_POST["company"];
    $linkedin = trim($_POST["linkedin"]);

    // Check if email already exists
    $checkEmail = "SELECT id FROM alumni WHERE email=?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "This email is already registered!";
    } else {
        // Insert new alumni data
        $sql = "INSERT INTO alumni (name, email, password, job_title, company, linkedin) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $password, $jobTitle, $company, $linkedin);

        if ($stmt->execute()) {
            $_SESSION["alumni_id"] = $stmt->insert_id;
            header("Location: 09-ALUMNI-HOMEPAGE.php");
            exit();
        } else {
            $error = "Signup failed, please try again!";
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
    <title>Alumni Signup - Alumni Networking Platform</title>
    <link rel="stylesheet" href="05-ALUMNI-SIGNUP.css">
</head>
<body>
    <div class="signup-container">
        <h2>Alumni Signup</h2>

        <?php if (!empty($error)): ?>
            <div style="color: red; font-weight: bold; margin-bottom: 10px;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form id="signupForm" method="POST" action="05-ALUMNI-SIGNUP.php">
            <input type="text" id="name" name="name" placeholder="Full Name" required>
            <small id="nameError" class="error"></small>

            <input type="email" id="email" name="email" placeholder="Email Address*" required>
            <small id="emailError" class="error"></small>

            <input type="password" id="password" name="password" placeholder="Password" required>
            <small id="passwordError" class="error"></small>

            <select id="jobTitle" name="jobTitle" required>
                <option value="">Select Job Title</option>
                <option value="Project Manager">Project Manager</option>
                <option value="Software Engineer">Software Engineer</option>
                <option value="Data Analyst">Data Analyst</option>
            </select>
            <small id="jobTitleError" class="error"></small>

            <select id="company" name="company" required>
                <option value="">Select Company</option>
                <option value="Systems Limited">Systems Limited</option>
                <option value="Google">Google</option>
                <option value="Microsoft">Microsoft</option>
            </select>
            <small id="companyError" class="error"></small>

            <input type="url" id="linkedin" name="linkedin" placeholder="LinkedIn Profile URL" required>
            <small id="linkedinError" class="error"></small>

            <button type="submit">Signup</button>
        </form>

        <div class="links">
            <a href="04-ALUMNI-LOGIN.php">Already have an account? Login</a>
        </div>
    </div>

    <script>
        document.getElementById("signupForm").addEventListener("submit", function(event) {
            let name = document.getElementById("name").value.trim();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value.trim();
            let jobTitle = document.getElementById("jobTitle").value;
            let company = document.getElementById("company").value;
            let linkedin = document.getElementById("linkedin").value.trim();

            let nameError = document.getElementById("nameError");
            let emailError = document.getElementById("emailError");
            let passwordError = document.getElementById("passwordError");
            let jobTitleError = document.getElementById("jobTitleError");
            let companyError = document.getElementById("companyError");
            let linkedinError = document.getElementById("linkedinError");

            nameError.innerText = "";
            emailError.innerText = "";
            passwordError.innerText = "";
            jobTitleError.innerText = "";
            companyError.innerText = "";
            linkedinError.innerText = "";

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

            if (jobTitle === "") {
                jobTitleError.innerText = "Please select a job title";
                isValid = false;
            }

            if (company === "") {
                companyError.innerText = "Please select a company";
                isValid = false;
            }

            if (!linkedin.match(/^https?:\/\/(www\.)?linkedin\.com\/.*$/)) {
                linkedinError.innerText = "Enter a valid LinkedIn profile URL";
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault(); // prevent actual form submission if invalid
            }
        });
    </script>
</body>
</html>
