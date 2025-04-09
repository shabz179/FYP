<?php
include 'db.php';
session_start(); // Starts the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect user inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Queries the database for the email
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // Verifies the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];

            // Redirect to the dessert dashboard
            header("Location: DESSERT_DASHBOARD.php");
            exit(); // Stop further execution after the redirect
        } else {
            echo "Invalid password. Please try again.";
        }
    } else {
        echo "No account found with this email. Please <a href='SIGN UP.php'>sign up</a>.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the page -->
    <title>Login</title>
    <!-- CSS design of the Page -->
    <link rel="stylesheet" href="LOGIN.CSS">
</head>
<body>
    <header class="header">
        <img src="IMAGES/Logo.png" alt="Bubble & Waffle Logo" class="logo">
        <!-- Navigation links -->
        <nav>
            <a href="HOMEPAGE.html">Homepage</a>
            <a href="CONTACT US.html">Contact Us</a>
        </nav>
    </header>
    
    <div class="container">
        <h2>Login</h2>
        <!-- Form submission to login.php using POST method -->
        <form action="LOGIN.php" method="POST">
            <!-- Entering user credentials -->
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label><br>
            <button type="submit">Login</button>  <!-- click on login button once filled in.-->
            <!-- Link to forgot password -->
            <br><a href="FORGOT PASSWORD.HTML">Forgot password?</a>
        </form>
        
        <div class="social-login">
            <p>OR</p>
            <!-- signing in through gmail button -->
            <a href="https://accounts.google.com/v3/signin/identifier?continue=https%3A%2F%2Fmail.google.com%2Fmail%2F%26ogbl%2F&emr=1&ltmpl=default&ltmplcache=2&osid=1&passive=true&rm=false&scc=1&service=mail&ss=1&ifkv=AVdkyDn3zGkXTD4n7R5AQrFiQ0hKCyt_pXYD7L2GNeVpySWZwzzBotmQRFlmglBJyWR2M4Rph4GqMg&ddm=1&flowName=GlifWebSignIn&flowEntry=ServiceLogin">
                <button class="google">Google</button>
            </a>
            <!-- signing in through facebook button -->
            <a href="https://en-gb.facebook.com">
                <button>Facebook</button>
            </a>
        </div>
        <!-- If customers don't have an account then they need to sign up. -->
        <p>Don't have an account? <a href="SIGN UP.php">Sign Up</a></p>
    </div>
</body>
</html>