<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | Viella Café</title>
    <meta name="description" content="Create a Viella Café account or sign in to manage your profile and orders.">
    <meta name="theme-color" content="#FFFFFF">
    <link rel="stylesheet" href="../CSS/style.css?v=4">

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="account-page">

    <header class="mobile-header">
        <button class="hamburger-btn" id="toggle-sidebar" type="button" aria-label="Toggle menu" aria-controls="sidebar" aria-expanded="false">
            <i data-lucide="menu"></i>
        </button>
        <img class="mobile-logo" src="../images/viella-cafe-logo.png" alt="Viella Café">
    </header>

    <div class="overlay" id="overlay"></div>

    <div class="app-layout">

        <!-- Shared navigation -->
        <aside class="sidebar" id="sidebar">
            <div>
                <div class="brand-header">
                    <img src="../images/viella-cafe-logo.png" alt="Viella Café Logo" class="navbar-logo">
                    <h1>Viella Café</h1>
                </div>

                <nav>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="../index.html">
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
                    <a href="Profile.php" class="nav-item">
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

        <!-- Account forms -->
        <main class="account-main">
            <section class="account-section">
                <div class="account-container">
                    <div class="account-intro">
                        <h1>Welcome to Viella Café</h1>
                        <p>Create an account or sign in to continue enjoying your favourite drinks and orders.</p>
                    </div>

                    <div class="account-card">
                        <!-- Switch between registration and sign-in. -->
                        <div class="account-tabs">
                            <button type="button" class="account-tab active" id="register-tab" onclick="showRegister()">
                                Create Account
                            </button>
                            <button type="button" class="account-tab" id="login-tab" onclick="showLogin()">
                                Sign In
                            </button>
                        </div>

                        <!-- New account details -->
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

                                                <!-- Existing account details -->
                        <form id="login-form" class="account-form hidden" onsubmit="handleLogin(event)">
                            <h2>Welcome Back</h2>
                            
                            <div class="form-group">
                                <label for="login-email">Email Address</label>
                                <input type="email" id="login-email" placeholder="Enter your email" required>
                            </div>

                            <div class="form-group">
                                <label for="login-password">Password</label>
                                <div class="password-wrapper">
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
    <script src="../JS/headerScroll.js"></script>
    <script>
        lucide.createIcons();
        setupMobileMenu();
    </script>
</body>
</html>