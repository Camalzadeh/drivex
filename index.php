<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Extracker - Login / Register</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<header class="header">
    <div class="header-container">
        <h1 class="logo">Extracker</h1>

        <nav class="auth-nav">
            <button id="show-login" class="nav-button active">Login</button>
            <button id="show-register" class="nav-button">Register</button>
        </nav>
    </div>
</header>

<main class="main-content">
    <div class="auth-container">

        <!-- LOGIN FORM -->
        <div id="login-form" class="auth-form login active">
            <h2>Welcome Back</h2>
            <div id="login-message"></div>

            <form id="loginForm">
                <input type="hidden" name="action" value="login">

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="submit-button">Sign In</button>
                <p class="form-footer">Forgot your password? <a href="#">Reset</a></p>
            </form>
        </div>


        <!-- REGISTER FORM -->
        <div id="register-form" class="auth-form register">
            <h2>Create Account</h2>
            <div id="register-message"></div>

            <form id="registerForm">
                <input type="hidden" name="action" value="register">

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit" class="submit-button">Sign Up</button>
            </form>
        </div>

    </div>
</main>

<script src="js/index.js"></script>

</body>
</html>
