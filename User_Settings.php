<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_details'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $email, $user_id);
    $stmt->execute();
    
    header("Location: user_settings.php");
    exit();
}

// Change password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Verify current password
    if (password_verify($current_password, $user['password'])) {
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user_id);
        $stmt->execute();
        
        header("Location: user_settings.php?success=password_updated");
        exit();
    } else {
        $error = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Settings</title>
</head>
<body>
    <h1>User Settings</h1>

    <!-- Update Details Form -->
    <h2>Update Details</h2>
    <form method="post">
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        <button type="submit" name="update_details">Update</button>
    </form>

    <!-- Change Password Form -->
    <h2>Change Password</h2>
    <form method="post">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <button type="submit" name="change_password">Change Password</button>
    </form>

    <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
</body>
</html>

<?php $conn->close(); ?>