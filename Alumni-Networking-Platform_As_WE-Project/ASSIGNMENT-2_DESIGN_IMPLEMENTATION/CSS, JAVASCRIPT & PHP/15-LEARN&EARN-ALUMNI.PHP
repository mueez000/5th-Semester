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

// Handle job posting submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["jobTitle"])) {
    $title = $_POST["jobTitle"];
    $company = $_POST["jobCompany"];
    $location = $_POST["jobLocation"];
    $employment_type = $_POST["jobType"];
    $deadline = $_POST["jobDeadline"];
    $website = $_POST["jobWebsite"];
    $experience = $_POST["jobExperience"];
    $skills = $_POST["jobSkills"];
    $description = $_POST["jobDescription"];
    $documents = $_POST["jobDocuments"];
    $alumni_id = $_SESSION["alumni_id"] ?? null;

    if (!$alumni_id) {
        header("Location: 04-ALUMNI-LOGIN.php");
        exit();
    }

    $sql = "INSERT INTO jobs (title, company, location, employment_type, deadline, website, experience, skills, description, documents, alumni_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $title, $company, $location, $employment_type, $deadline, $website, $experience, $skills, $description, $documents, $alumni_id);

    if ($stmt->execute()) {
        echo "<script>alert('Job posted successfully!'); window.location.href='15-LEARN&EARN-ALUMNI.php';</script>";
    } else {
        echo "<script>alert('Failed to post job. Please try again!');</script>";
    }

    $stmt->close();
    $conn->close();
    exit();
}

// Handle freelance project submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["projectTitle"])) {
    $title = $_POST["projectTitle"];
    $company = $_POST["projectCompany"];
    $location = $_POST["projectLocation"];
    $employment_type = $_POST["projectType"];
    $deadline = $_POST["projectDeadline"];
    $contact_email = $_POST["projectEmail"];
    $experience = $_POST["projectExperience"];
    $skills = $_POST["projectSkills"];
    $description = $_POST["projectDescription"];
    $documents = $_POST["projectDocuments"];
    $alumni_id = $_SESSION["alumni_id"] ?? null;

    if (!$alumni_id) {
        header("Location: 04-ALUMNI-LOGIN.php");
        exit();
    }

    $sql = "INSERT INTO freelance_projects (title, company, location, employment_type, deadline, contact_email, experience, skills, description, documents, alumni_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $title, $company, $location, $employment_type, $deadline, $contact_email, $experience, $skills, $description, $documents, $alumni_id);

    if ($stmt->execute()) {
        echo "<script>alert('Freelance project submitted successfully!'); window.location.href='15-LEARN&EARN-ALUMNI.php';</script>";
    } else {
        echo "<script>alert('Failed to submit project. Please try again!');</script>";
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Alumni Job & Freelance Posting - LearnerCanvas</title>
  <link rel="stylesheet" href="15-LEARN&EARN-ALUMNI.css">
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
        <li><a href="04-ALUMNI-LOGIN.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <div class="toggle-container">
      <span class="toggle-label">Job</span>
      <label class="toggle-switch">
        <input type="checkbox" id="toggleSwitch" onclick="toggleForm()">
        <span class="slider"></span>
      </label>
      <span class="toggle-label">Freelance</span>
    </div>

    <!-- Job Posting Form -->
    <form id="jobForm" class="form-section active-form" onsubmit="return validateJobForm()">
      <h2>Post Alumni Job Opportunity</h2>
      <div class="form-row">
        <div class="form-group">
          <label>Job Title</label>
          <input type="text" id="jobTitle" placeholder="Enter job title" required>
        </div>
        <div class="form-group">
          <label>Company Name</label>
          <input type="text" id="jobCompany" placeholder="Enter company name" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Job Location</label>
          <input type="text" id="jobLocation" placeholder="Enter location" required>
        </div>
        <div class="form-group">
          <label>Employment Type</label>
          <select id="jobType">
            <option>Full-time</option>
            <option>Part-time</option>
            <option>Contract</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Application Deadline</label>
          <input type="date" id="jobDeadline" required>
        </div>
        <div class="form-group">
          <label>Website</label>
          <input type="url" id="jobWebsite" placeholder="Company website">
        </div>
      </div>

      <div class="form-group">
        <label>Experience Required</label>
        <input type="text" id="jobExperience" placeholder="Enter experience requirements">
      </div>

      <div class="form-group">
        <label>Skills & Interests</label>
        <textarea id="jobSkills" rows="3" placeholder="List required skills"></textarea>
      </div>

      <div class="form-group">
        <label>Job Description</label>
        <textarea id="jobDescription" rows="5" placeholder="Detailed job description" required></textarea>
      </div>

      <div class="form-group">
        <label>Required Documents</label>
        <input type="text" id="jobDocuments" placeholder="List required documents">
      </div>

      <div class="form-actions">
        <button type="submit" class="submit-btn">Post Job</button>
      </div>
    </form>

    <!-- Freelance Project Form -->
    <form id="freelanceForm" class="form-section" style="display:none;" onsubmit="return validateFreelanceForm()">
      <h2>Create Freelance Project</h2>
      <div class="form-row">
        <div class="form-group">
          <label>Project Title</label>
          <input type="text" id="projectTitle" placeholder="Enter project title" required>
        </div>
        <div class="form-group">
          <label>Company Name</label>
          <input type="text" id="projectCompany" value="Main Media Software No." readonly>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Project Location</label>
          <input type="text" id="projectLocation" value="Online" readonly>
        </div>
        <div class="form-group">
          <label>Employment Type</label>
          <select id="projectType">
            <option>Intervals</option>
            <option>Fixed-term</option>
            <option>Ongoing</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Project Deadline</label>
          <input type="number" id="projectDeadline" value="7" min="1" placeholder="Days" required>
        </div>
        <div class="form-group">
          <label>Website</label>
          <input type="email" id="projectEmail" placeholder="Provides email" required>
        </div>
      </div>

      <div class="form-group">
        <label>Experience Req</label>
        <input type="text" id="projectExperience" value="Tokens" readonly>
      </div>

      <div class="form-group">
        <label>Skills & Interests Req</label>
        <textarea id="projectSkills" rows="3" placeholder="Required skills"></textarea>
      </div>

      <div class="form-group">
        <label>Project Description</label>
        <textarea id="projectDescription" rows="5" placeholder="Detailed project description" required></textarea>
      </div>

      <div class="form-group">
        <label>Required Documents</label>
        <input type="text" id="projectDocuments" value="Feedbacked by files" readonly>
      </div>

      <div class="form-actions">
        <button type="submit" class="submit-btn">Submit Project</button>
      </div>
    </form>
  </main>

  <footer>
    <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
  </footer>

  <script>
    function toggleForm() {
        const jobForm = document.getElementById('jobForm');
        const freelanceForm = document.getElementById('freelanceForm');
        const toggle = document.getElementById('toggleSwitch').checked;

        jobForm.style.display = toggle ? 'none' : 'block';
        freelanceForm.style.display = toggle ? 'block' : 'none';
    }

    document.getElementById("toggleSwitch").addEventListener("change", toggleForm);
    toggleForm();

    document.getElementById("jobForm").addEventListener("submit", function (event) {
        event.preventDefault();
        if (validateForm("jobForm")) {
            this.submit();
        }
    });

    document.getElementById("freelanceForm").addEventListener("submit", function (event) {
        event.preventDefault();
        if (validateForm("freelanceForm")) {
            this.submit();
        }
    });

    function validateForm(formId) {
        let form = document.getElementById(formId);
        let inputs = form.querySelectorAll("input, textarea, select");
        let isValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute("required") && !input.value.trim()) {
                showError(input, "This field is required");
                isValid = false;
            }
        });

        return isValid;
    }

    function showError(field, message) {
        alert(message);
        field.focus();
    }
</script>
</body>
</html>
