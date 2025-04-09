<?php
include 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize user inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if ($password !== $confirm_password) {
        die("Passwords do not match. Please try again.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email already exists
    $email_check_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $email_check_result = mysqli_query($conn, $email_check_query);

    if (mysqli_num_rows($email_check_result) > 0) {
        die("Email already exists. Please use a different email.");
    }

    // Insert the user data into the database
    $insert_query = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$hashed_password')";

    if (mysqli_query($conn, $insert_query)) {
        echo "Sign-up successful! You can now <a href='LOGIN.php'>log in</a>.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the page -->
    <title>Sign Up</title>
    <!-- CSS design of the website. -->
    <link rel="stylesheet" href="SIGN UP.CSS">
</head>
<body>
    <header class="header">
        <img src="IMAGES/Logo.png" alt="Bubble & Waffle Logo" class="logo">
        <!-- Navigation bars -->
        <nav>
            <a href="HOMEPAGE.html">Homepage</a>
            <a href="CONTACT US.html">Contact Us</a>
        </nav>
    </header>

    <div class="container">
        <h2>Sign Up</h2>
        <!-- Form submission to php page. -->
        <form action="SIGN UP.php" method="POST"> 
            <!-- Entering credentials when making the account. -->
            <label for="first-name">First Name:</label>
            <input type="text" id="first-name" name="first_name" required><br>

            <label for="last-name">Last Name:</label>
            <input type="text" id="last-name" name="last_name" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <!-- Creating a password and including special characters -->
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required 
                   pattern="(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{10,}" 
                   title="Password must be at least 10 characters long, contain at least one uppercase letter, one number, and one special character."><br>

            <label for="confirm-password">Re-type password:</label>
            <input type="password" id="confirm-password" name="confirm_password" required><br>
            <!-- Users need to agree to the terms and conditions before making the account -->
            <label>
                <input type="checkbox" name="terms" required> I agree to the <a href="TERMS AND CONDITIONS.HTML" target="_blank">Terms and Conditions</a>
            </label><br>
            <!-- submit the button which filled out. -->
            <button type="submit">Sign Up</button>
        </form>
        <!-- Creating a password and including special characters  -->
        <div class="password-requirements">
            <h3>Password Requirements:</h3>
            <ul>
                <li>At least 10 characters long</li>
                <li>At least one uppercase letter</li>
                <li>At least one number</li>
                <li>At least one special character</li>
            </ul>
        </div>
        <div class="social-login">
            <p>OR</p>
            <!-- Button to sign in using gmail -->
            <a href="https://accounts.google.com/v3/signin/identifier?continue=https%3A%2F%2Fmail.google.com%2Fmail%2F%26ogbl%2F&emr=1&ltmpl=default&ltmplcache=2&osid=1&passive=true&rm=false&scc=1&service=mail&ss=1&ifkv=AVdkyDn3zGkXTD4n7R5AQrFiQ0hKCyt_pXYD7L2GNeVpySWZwzzBotmQRFlmglBJyWR2M4Rph4GqMg&ddm=1&flowName=GlifWebSignIn&flowEntry=ServiceLogin">
                <button class="google">Google</button>
            </a>
            <!-- Button to sign in using facebook -->
            <a href="https://en-gb.facebook.com">
                <button>Facebook</button>
            </a>
        </div>
        <!-- If account made, links to login page for existing users -->
        <p>Already have an account? <a href="LOGIN.php">Login</a></p>
    </div>
</body>
</html>