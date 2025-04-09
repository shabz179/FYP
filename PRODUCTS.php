<?php
include 'db.php';

// Get query parameters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'name_asc';

// Base query
$sql = "SELECT * FROM dessert_Products WHERE 1";

// Apply search
if (!empty($search)) {
    $term = $conn->real_escape_string($search);
    $sql .= " AND (name LIKE '%$term%' OR description LIKE '%$term%')";
}

// Apply category filter
if (!empty($category)) {
    $cat = $conn->real_escape_string($category);
    $sql .= " AND category = '$cat'";
}

// Apply sorting
switch ($sort) {
    case 'name_desc':
        $sql .= " ORDER BY name DESC";
        break;
    case 'price_asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY price DESC";
        break;
    default:
        $sql .= " ORDER BY name ASC";
        break;
}

$result = $conn->query($sql);

// Fetch categories dynamically (optional: for dropdown)
$category_query = "SELECT DISTINCT category FROM dessert_Products ORDER BY category";
$category_result = $conn->query($category_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Our Desserts</title>
    <link rel="stylesheet" href="PRODUCTS.CSS">
</head>
<body>

<header>
    <img src="IMAGES/Logo.png" alt="Bubble & Waffle Logo">
    <nav>
        <a href="HOMEPAGE.html">Home</a>
        <a href="CONTACT US.html">Contact</a>
        <a href="https://www.instagram.com/bubble_waffle21/?hl=en" target="_blank">
            <img src="IMAGES/Instagram_logo.webp" alt="Instagram" style="height: 40px;">
            <a href="LOGIN.php">Login</a>
        </a>
    </nav>
</header>

<main>
    <h1>Our Desserts</h1>
    <p class="subtitle">Explore our delicious selection of sweet treats below!</p>

    <!-- Filter & Search Section -->
    <form method="GET" action="PRODUCTS.php">
        <div class="filters">
            <select name="category">
                <option value="">All Categories</option>
                <?php while ($catRow = $category_result->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($catRow['category']); ?>" <?php if ($catRow['category'] == $category) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($catRow['category']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="sort">
                <option value="name_asc" <?php if ($sort == 'name_asc') echo 'selected'; ?>>A-Z</option>
                <option value="name_desc" <?php if ($sort == 'name_desc') echo 'selected'; ?>>Z-A</option>
                <option value="price_asc" <?php if ($sort == 'price_asc') echo 'selected'; ?>>Price: Low to High</option>
                <option value="price_desc" <?php if ($sort == 'price_desc') echo 'selected'; ?>>Price: High to Low</option>
            </select>
        </div>

        <div class="search-bar">
            <input type="search" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Apply</button>
        </div>
    </form>

    <!-- Product List -->
    <div class="product-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['name']; ?>">
                    <h2><?php echo $row['name']; ?></h2>
                    <p><?php echo $row['description']; ?></p>
                    <p class="price">£<?php echo number_format($row['price'], 2); ?></p>

                    <label>Quantity:</label>
                    <input type="number" name="quantity" min="1" value="1">

                    <button class="add-to-cart"
                            data-id="<?php echo $row['id']; ?>"
                            data-name="<?php echo $row['name']; ?>"
                            data-price="<?php echo $row['price']; ?>"
                            data-image="<?php echo $row['image_url']; ?>">
                        Add to Cart
                    </button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-results">No desserts found.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <a href="HOMEPAGE.html">Back to Home</a>
    <p>&copy; 2025 Bubble & Waffle</p>
</footer>

<script src="CART.JS"></script>
</body>
</html>