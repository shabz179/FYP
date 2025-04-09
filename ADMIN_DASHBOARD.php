<?php
include 'db.php';
session_start();
// Connect to database
$conn = new mysqli("localhost", "root", "", "admin_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    
    <nav>
        <ul>
            <li><a href="Manage_Desserts.php">Desserts</a></li>
            <li><a href="Manage_Orders.php">Orders</a></li>
            <li><a href="Manage_Users.php">Users</a></li>
            <li><a href="Admin_Settings.php">Settings</a></li>
            <li><a href="Report.php">Reports</a></li>
            <li><a href="LOGOUT.php">Logout</a></li>
        </ul>
    </nav>
    
    <h2>Welcome, Admin</h2>
    <p>Use the menu to manage the system.</p>
</body>
</html>

<?php $conn->close(); ?>
