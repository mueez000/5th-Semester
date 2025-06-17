<?php
session_start();

// Logout handler
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: 01-LANDING-PAGE.php"); // Redirect to landing page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fundraising - LearnerCanvas</title>
    <link rel="stylesheet" href="16-FUNDRAIZING.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" alt="LearnerCanvas Logo">
            <h1>LearnerCanvas</h1>
        </div>
        <nav>
            <ul>
                <li><a href="?logout=true">Logout</a></li> <!-- Logout handled via PHP -->
            </ul>
        </nav>
    </header>

    <main>
        <h2>Support Startup Fundraising Initiatives</h2>
        <div id="campaignList" class="campaign-list"></div>

        <div class="navigation">
            <button class="nav-btn" onclick="window.location.href='previous-page.html'">Back</button>
            <button class="nav-btn" onclick="window.location.href='next-page.html'">Next</button>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 LearnerCanvas. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let campaigns = [
                { id: 1, title: "Pakistani EdTech Fund", description: "Support innovative student-led EdTech solutions.", image: "fund1.jpg" },
                { id: 2, title: "AI Research for Pakistan", description: "Help fund AI research for better learning solutions.", image: "fund2.jpg" },
                { id: 3, title: "Green Energy Startups", description: "Invest in startups working on clean energy in Pakistan.", image: "fund3.jpg" },
                { id: 4, title: "Smart Agriculture Solutions", description: "Fund tech solutions improving agriculture in Pakistan.", image: "fund4.jpg" },
                { id: 5, title: "Healthcare Innovation Fund", description: "Support startups working on better healthcare tech.", image: "fund5.jpg" },
                { id: 6, title: "Women in Tech Pakistan", description: "Empower women-led tech startups in Pakistan.", image: "fund6.jpg" }
            ];

            let campaignList = document.getElementById("campaignList");

            campaigns.forEach(campaign => {
                let campaignCard = document.createElement("div");
                campaignCard.classList.add("campaign-card");

                campaignCard.innerHTML = `
                    <img src="${campaign.image}" alt="${campaign.title}">
                    <div class="campaign-details">
                        <h3>${campaign.title}</h3>
                        <p>${campaign.description}</p>
                        <input type="number" id="amount-${campaign.id}" placeholder="Enter donation amount ($)" required>
                        <button class="donate-btn">Donate</button>
                        <small class="confirmation-message" style="display: none; color: green;">Donation Successful ✅</small>
                    </div>
                `;

                campaignList.appendChild(campaignCard);

                let donateButton = campaignCard.querySelector(".donate-btn");
                let confirmationMessage = campaignCard.querySelector(".confirmation-message");

                donateButton.addEventListener("click", function () {
                    console.log(`Donate function triggered for: ${campaign.title}`); // ✅ Debugging Log

                    let amount = document.getElementById(`amount-${campaign.id}`).value.trim();
                    if (isNaN(amount) || amount < 1) {
                        alert("Donation amount must be at least $1.");
                        return;
                    }

                    let donatedCampaigns = JSON.parse(sessionStorage.getItem("donatedCampaigns")) || [];
                    if (donatedCampaigns.includes(campaign.id)) {
                        alert("You have already donated to this campaign.");
                        return;
                    }

                    let confirmDonation = confirm(`Are you sure you want to donate $${amount} to ${campaign.title}?`);
                    if (!confirmDonation) return;

                    donatedCampaigns.push(campaign.id);
                    sessionStorage.setItem("donatedCampaigns", JSON.stringify(donatedCampaigns));

                    confirmationMessage.style.display = "block"; // ✅ Show Confirmation
                    donateButton.innerText = "Donated";
                    donateButton.disabled = true;
                    donateButton.style.backgroundColor = "#ccc"; // ✅ Button Disabled
                    alert("Your donation has been submitted successfully!");
                });
            });
        });
    </script>
</body>
</html>