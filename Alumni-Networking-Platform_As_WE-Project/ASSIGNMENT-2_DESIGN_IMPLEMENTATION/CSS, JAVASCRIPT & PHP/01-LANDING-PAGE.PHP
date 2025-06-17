<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Networking Platform</title>
    <link rel="stylesheet" href="01-landing-page.css">
</head>
<body>
    <h1>Alumni Network</h1>

    <main>
        <section id="choose-role" class="section">
            <h2>Choose Your Role. Start Your Journey.</h2>
            <div class="cards">
                <div class="card">
                    <h3>Student</h3>
                    <p>Connect with alumni and explore mentorship opportunities.</p>
                    <button onclick="confirmRedirect('student')">Go to Student Page</button>
                </div>
                <div class="card">
                    <h3>Alumni</h3>
                    <p>Engage with students and contribute to the alumni community.</p>
                    <button onclick="confirmRedirect('alumni')">Go to Alumni Page</button>
                </div>
                <div class="card">
                    <h3>Admin</h3>
                    <p>Manage platform activities and ensure smooth operations.</p>
                    <button onclick="confirmRedirect('admin')">Go to Admin Page</button>
                </div>
            </div>
        </section>
    </main>

    <script>
        function confirmRedirect(role) {
            const rolePaths = {
                student: "02-STUDENT-LOGIN.php",
                alumni: "04-ALUMNI-LOGIN.php",
                admin: "06-ADMIN-LOGIN.php"
            };

            if (rolePaths[role]) {
                window.location.href = rolePaths[role];
            } else {
                alert("Error: Invalid role selection!");
            }
        }
    </script>
</body>
</html>