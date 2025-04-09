<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $conn->query("UPDATE orders SET status = '$status' WHERE order_id = $order_id");
    header("Location: manage_orders.php");
    exit();
}

// Fetch orders
$orders = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Orders</title>
</head>
<body>
    <h1>Manage Orders</h1>
    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Status</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $orders->fetch_assoc()) { ?>
            <tr>
                <td>#<?php echo $row['order_id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo ucfirst($row['status']); ?></td>
                <td>$<?php echo number_format($row['total'], 2); ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                        <select name="status">
                            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="completed" <?php if ($row['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                            <option value="cancelled" <?php if ($row['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>