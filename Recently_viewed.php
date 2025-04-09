<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch recently viewed desserts
$stmt = $conn->prepare("SELECT d.id, d.name, d.description, d.image, d.price 
                         FROM recently_viewed rv 
                         JOIN desserts d ON rv.dessert_id = d.id 
                         WHERE rv.user_id = ? 
                         ORDER BY rv.viewed_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$recently_viewed = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recently Viewed Desserts</title>
    <link rel="stylesheet" href="Recently_viewed.css"> 
</head>
<body>
    <h1>Recently Viewed Desserts</h1>

    <?php if (count($recently_viewed) > 0): ?>
        <div class="favorites-container">
            <?php foreach ($recently_viewed as $dessert): ?>
                <div class="dessert-card">
                    <img src="<?= htmlspecialchars($dessert['image']); ?>" alt="<?= htmlspecialchars($dessert['name']); ?>">
                    <h3><?= htmlspecialchars($dessert['name']); ?></h3>
                    <p><?= htmlspecialchars($dessert['description']); ?></p>
                    <p class="price">$<?= htmlspecialchars(number_format($dessert['price'], 2)); ?></p>
                    <a href="order.php?dessert_id=<?= htmlspecialchars($dessert['id']); ?>">Order Now</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>You have not viewed any desserts yet.</p>
    <?php endif; ?>

    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

<?php $conn->close(); ?>