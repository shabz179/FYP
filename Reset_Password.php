<?php
include 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_GET['token'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $newPassword = $_POST["new_password"];

    // Validate password length (at least 8 characters)
    if (strlen($newPassword) < 8) {
        echo "Password must be at least 8 characters long.";
        exit();
    }

    // Hash new password
    $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Find the reset request
    $stmt = $conn->prepare("SELECT email, expires_at FROM reset_Password WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();

    if (!$reset) {
        echo "Invalid token.";
        exit();
    }

    // Check if token has expired
    if (strtotime($reset["expires_at"]) === false || strtotime($reset["expires_at"]) < time()) {
        echo "Token has expired or is invalid.";
        exit();
    }

    $email = $reset["email"];

    // Update user's password
    $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->bind_param("ss", $newPassword, $email);
    $update->execute();

    // Delete token
    $delete = $conn->prepare("DELETE FROM reset_Password WHERE token = ?");
    $delete->bind_param("s", $token);
    $delete->execute();

    echo "Your password has been reset successfully.";
    exit();
}
?>

<!-- Password Reset Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <input type="password" name="new_password" placeholder="Enter new password" required><br>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>