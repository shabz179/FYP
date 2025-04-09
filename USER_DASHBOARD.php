<?php
include 'db.php'; // Include your database connection
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit(); 
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "user_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="USER_DASHBOARD.CSS">
</head>
<body>
    <h1>User Dashboard</h1>
    
    <nav>
        <ul>
            <li><a href="HOMEPAGE.html">Home</a></li>
            <li><a href="User_Settings.php">User Settings</a></li>
            <li><a href="My_Orders.php">My Orders</a></li>
            <li><a href="Recently_viewed.php">Recently Viewed</a></li>
            <li><a href="CONTACT US.html">Contact Us</a></li>
            <li><a href="LOGOUT.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
        </ul>
    </nav>
    
    <h2>Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h2>
    <p>Use the menu to navigate through your account.</p>
    
    <h3>Your Recent Orders</h3>
    <?php
    // Fetch recent orders
    $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();

    if ($orders->num_rows > 0) {
        echo "<ul>";
        while ($order = $orders->fetch_assoc()) {
            echo "<li>Order ID: " . htmlspecialchars($order['id']) . " - Date: " . htmlspecialchars($order['order_date']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No recent orders found.</p>";
    }
    ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>