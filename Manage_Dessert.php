<?php
session_start();
include 'db.php';

// Redirect if not admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Add & Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? null;
    $name = trim(htmlspecialchars($_POST['name']));
    $description = trim(htmlspecialchars($_POST['description']));
    $price = floatval($_POST['price']);
    
    // Handle Image Upload
    $image = "uploads/default.jpg"; // Default image
    if (!empty($_FILES['image']['name'])) {
        $image = "uploads/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image);
    } elseif ($id) {
        // Keep old image when updating
        $stmt = $conn->prepare("SELECT image FROM desserts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $image = $stmt->get_result()->fetch_assoc()['image'];
    }

    if ($id) {
        $stmt = $conn->prepare("UPDATE desserts SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $image, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO desserts (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $image);
    }

    $stmt->execute();
    header("Location: manage_desserts.php");
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM desserts WHERE id = ?");
    $stmt->bind_param("i", $_GET['delete']);
    $stmt->execute();
    header("Location: manage_desserts.php");
    exit();
}

// Fetch desserts
$result = $conn->query("SELECT * FROM desserts");

// Fetch data for editing
$editDessert = isset($_GET['edit']) ? $conn->query("SELECT * FROM desserts WHERE id = {$_GET['edit']}")->fetch_assoc() : null;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Desserts</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Manage Desserts</h1>

    <!-- Add & Update Form -->
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $editDessert['id'] ?? ''; ?>">
        <input type="text" name="name" placeholder="Dessert Name" value="<?= $editDessert['name'] ?? ''; ?>" required>
        <input type="text" name="description" placeholder="Description" value="<?= $editDessert['description'] ?? ''; ?>" required>
        <input type="number" name="price" step="0.01" placeholder="Price" value="<?= $editDessert['price'] ?? ''; ?>" required>
        <input type="file" name="image">
        <button type="submit"><?= $editDessert ? 'Update' : 'Add'; ?> Dessert</button>
    </form>

    <h2>All Desserts</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Dessert</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= $row['description']; ?></td>
            <td>$<?= number_format($row['price'], 2); ?></td>
            <td><img src="<?= $row['image']; ?>" width="50"></td>
            <td>
                <a href="?edit=<?= $row['id']; ?>">Edit</a> | 
                <a href="?delete=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
