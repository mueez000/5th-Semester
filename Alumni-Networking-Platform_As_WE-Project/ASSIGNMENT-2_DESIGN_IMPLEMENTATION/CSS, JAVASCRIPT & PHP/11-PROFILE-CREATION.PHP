<?php
session_start();
include 'config.php';

function generateUniqueFilename($path, $filename) {
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $counter = 1;
    $newName = $filename;

    while (file_exists($path . $newName)) {
        $newName = $name . "_" . $counter . "." . $ext;
        $counter++;
    }
    return $newName;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $role = $_POST["role"];
    $education = trim($_POST["education"]);
    $jobTitle = trim($_POST["jobTitle"]);
    $company = trim($_POST["company"]);
    $linkedin = trim($_POST["linkedin"]);
    $experience = trim($_POST["experience"]);
    $skills = trim($_POST["skills"]);
    $description = trim($_POST["description"]);

    // File upload paths
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Profile Picture Upload
    $photoPath = "";
    if (!empty($_FILES["photo"]["name"])) {
        $photoFilename = basename($_FILES["photo"]["name"]);
        $photoFilename = generateUniqueFilename($uploadDir, $photoFilename);
        $photoPath = $uploadDir . $photoFilename;

        if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath)) {
            echo "Failed to upload photo.";
            exit();
        }
    }

    // Resume Upload
    $resumePath = "";
    if (!empty($_FILES["resume"]["name"])) {
        $resumeFilename = basename($_FILES["resume"]["name"]);
        $resumeFilename = generateUniqueFilename($uploadDir, $resumeFilename);
        $resumePath = $uploadDir . $resumeFilename;

        if (!move_uploaded_file($_FILES["resume"]["tmp_name"], $resumePath)) {
            echo "Failed to upload resume.";
            exit();
        }
    }

    // Check if profile with this email already exists
    $checkSql = "SELECT id, photo, resume FROM profiles WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing profile
        $row = $result->fetch_assoc();
        $profileId = $row['id'];

        // Keep old files if no new uploaded
        $finalPhotoPath = $photoPath !== "" ? $photoPath : $row['photo'];
        $finalResumePath = $resumePath !== "" ? $resumePath : $row['resume'];

        $updateSql = "UPDATE profiles SET name=?, role=?, education=?, job_title=?, company=?, linkedin=?, experience=?, skills=?, description=?, photo=?, resume=?, profile_completed=1 WHERE id=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssssssssssi", $name, $role, $education, $jobTitle, $company, $linkedin, $experience, $skills, $description, $finalPhotoPath, $finalResumePath, $profileId);

        if (!$updateStmt->execute()) {
            echo "Profile update failed. Please try again!";
            exit();
        }
        $updateStmt->close();

    } else {
        // Insert new profile
        $insertSql = "INSERT INTO profiles (name, email, role, education, job_title, company, linkedin, experience, skills, description, photo, resume, profile_completed) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ssssssssssss", $name, $email, $role, $education, $jobTitle, $company, $linkedin, $experience, $skills, $description, $photoPath, $resumePath);

        if (!$insertStmt->execute()) {
            echo "Profile creation failed. Please try again!";
            exit();
        }
        $insertStmt->close();
    }
    $checkStmt->close();

    // Update profile_completed flag in students or alumni table based on session
    if ($role === "Student" && isset($_SESSION["student_id"])) {
        $student_id = $_SESSION["student_id"];
        $updateStudentSql = "UPDATE students SET profile_completed = 1 WHERE id = ?";
        $updateStudentStmt = $conn->prepare($updateStudentSql);
        $updateStudentStmt->bind_param("i", $student_id);
        $updateStudentStmt->execute();
        $updateStudentStmt->close();
    } elseif ($role === "Alumnus" && isset($_SESSION["alumni_id"])) {
        $alumni_id = $_SESSION["alumni_id"];
        $updateAlumniSql = "UPDATE alumni SET profile_completed = 1 WHERE id = ?";
        $updateAlumniStmt = $conn->prepare($updateAlumniSql);
        $updateAlumniStmt->bind_param("i", $alumni_id);
        $updateAlumniStmt->execute();
        $updateAlumniStmt->close();
    }

    $conn->close();

    // Redirect user based on role
    if ($role === "Student") {
        header("Location: 08-STUDENT-HOMEPAGE.php");
    } else {
        header("Location: 09-ALUMNI-HOMEPAGE.php");
    }
    exit();
}

// Logout handler
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: 06-ADMIN-LOGIN.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Create Profile - LearnerCanvas</title>
<link rel="stylesheet" href="11-PROFILE-CREATION.css" />
</head>
<body>
<header>
    <div class="logo">
        <img src="logo.png" alt="LearnerCanvas Logo" />
        <h1>LearnerCanvas</h1>
    </div>
    <nav>
        <ul>
            <li><a href="?logout=true">Logout</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Create Your Profile</h2>
    <form id="profileForm" enctype="multipart/form-data" method="POST" action="">
        <label for="photo">Upload Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*" />

        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required />

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required />

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option>Student</option>
            <option>Alumnus</option>
            <option>Faculty</option>
        </select>

        <label for="education">Education:</label>
        <textarea id="education" name="education"></textarea>

        <label for="jobTitle">Job Title:</label>
        <input type="text" id="jobTitle" name="jobTitle" />

        <label for="company">Company:</label>
        <input type="text" id="company" name="company" />

        <label for="linkedin">LinkedIn Profile:</label>
        <input type="url" id="linkedin" name="linkedin" />

        <label for="experience">Experience:</label>
        <textarea id="experience" name="experience"></textarea>

        <label for="skills">Skills:</label>
        <textarea id="skills" name="skills"></textarea>

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>

        <label for="resume">Upload Resume:</label>
        <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" />

        <button type="submit">Submit Profile</button>
    </form>
</main>

<footer>
    <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
</footer>

</body>
</html>
