<?php
session_start();
include 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch total sales
$sales = $conn->query("SELECT SUM(total) as total_sales FROM orders")->fetch_assoc();

// Fetch total orders
$total_orders = $conn->query("SELECT COUNT(*) as order_count FROM orders")->fetch_assoc();

// Fetch top-selling desserts
$top_desserts = $conn->query("SELECT desserts.name, COUNT(order_items.dessert_id) as count 
                              FROM order_items 
                              JOIN desserts ON order_items.dessert_id = desserts.id 
                              GROUP BY order_items.dessert_id 
                              ORDER BY count DESC 
                              LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Sales Reports</h1>
    
    <p><strong>Total Sales:</strong> $<?php echo number_format($sales['total_sales'], 2); ?></p>
    <p><strong>Total Orders:</strong> <?php echo $total_orders['order_count']; ?></p>
    
    <h2>Top-Selling Desserts</h2>
    <table border="1">
        <tr><th>Dessert</th><th>Orders</th></tr>
        <?php while ($row = $top_desserts->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo $row['count']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
