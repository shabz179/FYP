<?php
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the submitted email
$email = $_POST["email"] ?? '';

if (empty($email)) {
    echo "Please enter your email.";
    exit();
}

// Validate the email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Please enter a valid email address.";
    exit();
}

// Check if the email exists in the users table
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No user found with that email.";
    exit();
}

// Generate a unique token and expiration time
$token = bin2hex(random_bytes(32));
$expires_at = date("Y-m-d H:i:s", time() + 3600); // Valid for 1 hour

// Insert into reset_password table
$insert = $conn->prepare("INSERT INTO reset_password (email, token, expires_at) VALUES (?, ?, ?)");
$insert->bind_param("sss", $email, $token, $expires_at);
$insert->execute();

// Generate reset link
$resetLink = "http://localhost/reset_password.php?token=$token";

// Simulate sending email (in real app, use mail() or PHPMailer)
echo "<p>Password reset link has been generated:</p>";
echo "<a href='$resetLink'>$resetLink</a>";
?>