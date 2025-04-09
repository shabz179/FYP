<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: LOGIN.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet"  href="styles.css">
</head>
<body>
    <h1>My Orders</h1>

    <?php if (count($orders) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']); ?></td>
                        <td><?= htmlspecialchars($order['created_at']); ?></td> <!-- Displaying created_at for date and time -->
                        <td><?= htmlspecialchars($order['status']); ?></td>
                        <td><?= htmlspecialchars($order['total_amount']); ?></td>
                        <td><a href="order_details.php?order_id=<?= htmlspecialchars($order['id']); ?>">View Details</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no orders yet.</p>
    <?php endif; ?>

    <a href="USER_DASHBOARD.php">Back to Dashboard</a>
</body>
</html>

<?php $conn->close(); ?>