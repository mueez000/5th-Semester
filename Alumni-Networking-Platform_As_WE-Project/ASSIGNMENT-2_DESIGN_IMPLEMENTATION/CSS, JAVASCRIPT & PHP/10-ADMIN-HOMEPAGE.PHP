<?php
session_start();
include 'config.php';

// Fetch admin name for welcome message
$admin_id = $_SESSION["admin_id"] ?? null;
$admin_name = "Admin";

if ($admin_id) {
    $sql = "SELECT name FROM admins WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $admin_name = $row["name"];
    }
    $stmt->close();
}

// Logout handler
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: 06-ADMIN-LOGIN.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home - LearnerCanvas</title>
    <link rel="stylesheet" href="10-ADMIN-HOMEPAGE.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="LearnerCanvas Logo">
            <h1>LearnerCanvas Admin</h1>
        </div>
        <nav>
            <ul>
                <li><a href="10-ADMIN-HOMEPAGE.php">Home</a></li>
                <li><a href="?logout=true">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="admin-container">
        <div class="sidebar">
            <h3>Admin Menu</h3>
            <ul>
                <li><a href="dashboard.html">Dashboard</a></li>
                <li><a href="07-ADMIN-SIGNUP.php">New Admin</a></li>
                <li><a href="user-management.html">User Management</a></li>
                <li><a href="campaigns.html">Campaigns</a></li>
                <li><a href="analytics.html">Analytics</a></li>
                <li><a href="settings.html">Settings</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="welcome">
                <h2>Welcome back, <?php echo htmlspecialchars($admin_name); ?>!</h2>
                <p>Manage, Monitor, and Empower—Keeping the Platform Secure and Efficient.</p>
            </div>

            <div class="stats-grid">
                <div class="card">
                    <h3>Active Users</h3>
                    <p>3000 (10% increase in last 24 hours)</p>
                </div>
                <div class="card">
                    <h3>Pending Approvals</h3>
                    <p>24 (10 completed in last 24 hours)</p>
                </div>
            </div>

            <div class="actions-grid">
                <div class="card">
                    <h3>User Management</h3>
                    <p>Efficiently manage users with tools to view, edit, and remove accounts, ensuring platform security and seamless access control.</p>
                    <button onclick="window.location.href='user-management.html'">Manage Users</button>
                </div>
                <div class="card">
                    <h3>Create Campaigns</h3>
                    <p>Easily create and manage fundraising campaigns with customizable goals, secure payment options, and real-time progress tracking.</p>
                    <button onclick="window.location.href='campaigns.html'">Create Campaign</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
    </footer>

    <script>
        function redirectPage(page) {
            window.location.href = `?page=${page}`;
        }
    </script>
</body>
</html>