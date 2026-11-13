
<?php

require_once "../PHP/auth.php";

// Only logged-in customers can access this page
requireLogin();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile | Viella Café</title>
    <meta name="description" content="View and update your Viella Café profile and account preferences.">
    <meta name="theme-color" content="#F7F1E8">
    <link rel="stylesheet" href="../CSS/style.css">

    <script src="https://unpkg.com/lucide@latest"></script>

</head>

<body class="profile-page">

    <div class="app-layout">
        <div id="header"></div>

        <main class="dashboard-wrapper">

        <div class="dashboard-grid">

            <!-- Profile details -->

            <div class="profile-card">

                <div class="profile-avatar-box">

                    <img
                        src="../images/viella-cafe-logo.png"
                        alt="Profile Photo"
                        id="profileAvatar">

                </div>

                <div class="profile-header-row">

                    <h2>My Profile</h2>

                    <div class="last-login-info">

                        Status: Active<br>
                        Viella Café Rewards

                    </div>

                </div>

                <h3 id="profile-name-display">
                    Loading...
                </h3>

                <form class="profile-form" id="profileUpdateForm">

                    <div class="form-row-split">

                        <div class="form-group-line">

                            <input
                                type="text"
                                id="fullName"
                                placeholder="Full Name"
                                required>

                        </div>

                        <div class="form-group-line">

                            <input
                                type="tel"
                                id="phoneNumber"
                                placeholder="Phone Number">

                        </div>

                    </div>

                    <div class="form-group-line">

                        <input
                            type="email"
                            id="userEmail"
                            placeholder="Email Address"
                            readonly>

                    </div>

                    <div class="toggle-row">

                        <span class="toggle-label">
                            Order notifications &amp; promos
                        </span>

                        <input
                            type="checkbox"
                            id="notifications">

                    </div>

                    <button
                        type="submit"
                        class="btn-gradient-save">

                        Save Changes

                    </button>

                    <p
                        id="profile-message"
                        class="form-message">
                    </p>

                </form>

            </div>


            <!-- Account details and order history -->

            <div class="right-cards-stack">


                <div class="info-card">

                    <div class="card-top-bar">

                        <h3>My Viella Café Account</h3>

                    </div>

                    <div class="item-row">

                        <div>

                            <p class="item-title">
                                Active Account
                            </p>

                            <p
                                class="item-subtext"
                                id="displayEmailSub">

                                Loading...

                            </p>

                        </div>

                        <span class="badge-pill badge-success">
                            Active
                        </span>

                    </div>

                    <div class="item-row">

                        <div>

                            <p class="item-title">
                                Rewards Tier
                            </p>

                            <p class="item-subtext">
                                Gold Member • 120 pts
                            </p>

                        </div>

                        <span class="badge-pill badge-success">
                            Active
                        </span>

                    </div>

                    <div class="item-row">

                        <div>

                            <p class="item-title">
                                Account Security
                            </p>

                            <p class="item-subtext">
                                Your account is protected by a password.
                            </p>

                        </div>

                    </div>

                    <a
                        href="../PHP/logout.php"
                        class="badge-pill badge-danger logout-link">

                        Log Out

                    </a>

                </div>


                <div class="info-card">

                    <div class="card-top-bar">

                        <h3>Recent Orders</h3>

                    </div>

                    <div class="item-row">

                        <div>

                            <p class="item-title">
                                No recent orders
                            </p>

                            <p class="item-subtext">
                                Your orders will appear here.
                            </p>

                        </div>

                    </div>

                </div>


            </div>

        </div>

        </main>
    </div>

    <script src="../JS/account.js"></script>
    <script src="../JS/headerScroll.js"></script>

    <script>
        fetch("header.html")
            .then(response => {
                if (!response.ok) throw new Error("Header could not be loaded.");
                return response.text();
            })
            .then(header => {
                document.getElementById("header").innerHTML = header;
                lucide.createIcons();
                setupMobileMenu();
            })
            .catch(error => console.error("Header loading error:", error));
    </script>

</body>

</html>
