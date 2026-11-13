<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | Viella Café</title>

    <link rel="stylesheet" href="../CSS/root.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/account.css">
    <link rel="stylesheet" href="../CSS/footer.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alice&family=Lora:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="account-page">

    <div class="overlay" id="overlay"></div>

    <div class="app-layout">

        <!-- SIDEBAR (Unified with Cart / Home) -->
        <aside class="sidebar" id="sidebar">
            <div>
                <div class="brand-header">
                    <img src="../images/viella-cafe-logo.png" alt="Viella Café Logo" class="navbar-logo">
                    <h1>Viella Café</h1>
                </div>

                <nav>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="index.html">
                                <i data-lucide="home"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="menu.html">
                                <i data-lucide="Coffee"></i>
                                Menu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="cart.html">
                                <i data-lucide="shopping-bag"></i>
                                My Orders
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="sidebar-footer">
                <?php if(isset($_SESSION['user_name'])): ?>
                    <div class="welcome-user">
                        Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
                    </div>
                    <a href="profile.php" class="nav-item">
                        <i data-lucide="user"></i> My Profile
                    </a>
                    <a href="../PHP/logout.php" class="logout-btn">Logout</a>
                <?php else: ?>
                    <a href="account.php" class="nav-item active">
                        <i data-lucide="user"></i> Sign Up / Login
                    </a>
                <?php endif; ?>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="account-main">
            <section class="account-section">
                <div class="account-container">
                    <div class="account-intro">
                        <h1>Welcome to Viella Café</h1>
                        <p>Create an account or sign in to continue enjoying your favourite drinks and orders.</p>
                    </div>

                    <div class="account-card">
                        <!-- TABS -->
                        <div class="account-tabs">
                            <button type="button" class="account-tab active" id="register-tab" onclick="showRegister()">
                                Create Account
                            </button>
                            <button type="button" class="account-tab" id="login-tab" onclick="showLogin()">
                                Sign In
                            </button>
                        </div>

                        <!-- REGISTER FORM -->
                        <form id="register-form" class="account-form" onsubmit="handleRegister(event)">
                            <h2>Create Your Account</h2>
                            <p class="form-description">Join Viella Café and make every visit a little more personal.</p>

                            <div class="form-group">
                                <label for="register-name">Full Name</label>
                                <input type="text" id="register-name" placeholder="Enter your full name" required>
                            </div>

                            <div class="form-group">
                                <label for="register-email">Email Address</label>
                                <input type="email" id="register-email" placeholder="Enter your email" required>
                            </div>

                            <div class="form-group">
                                <label for="register-password">Password</label>
                                <div class="password-wrapper">
                                    <input type="password" id="register-password" placeholder="Create a password" minlength="8" required>
                                    <button type="button" class="show-password" onclick="togglePassword('register-password', this)">Show</button>
                                </div>
                                <small class="password-hint">Minimum 8 characters</small>
                            </div>

                            <div class="form-group">
                                <label for="confirm-password">Confirm Password</label>
                                <div class="password-wrapper">
                                    <input type="password" id="confirm-password" placeholder="Confirm password" minlength="8" required>
                                    <button type="button" class="show-password" onclick="togglePassword('confirm-password', this)">Show</button>
                                </div>
                            </div>

                            <button type="submit" class="account-btn">Create Account</button>
                            <p id="register-message" class="form-message"></p>
                        </form>

                                                <!-- LOGIN FORM -->
                        <form id="login-form" class="account-form hidden" onsubmit="handleLogin(event)">
                            <h2>Welcome Back</h2>
                            
                            <div class="form-group">
                                <label for="login-email">Email Address</label>
                                <!-- MAKE SURE ID IS EXACTLY login-email -->
                                <input type="email" id="login-email" placeholder="Enter your email" required>
                            </div>

                            <div class="form-group">
                                <label for="login-password">Password</label>
                                <div class="password-wrapper">
                                    <!-- MAKE SURE ID IS EXACTLY login-password -->
                                    <input type="password" id="login-password" placeholder="Enter your password" required>
                                    <button type="button" class="show-password" onclick="togglePassword('login-password', this)">Show</button>
                                </div>
                            </div>

                            <button type="submit" class="account-btn">Sign In</button>
                            <p id="login-message" class="form-message"></p>
                        </form>
                    </div>
                </div>
            </section>
        </main>

    </div>

    <script src="../JS/account.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>