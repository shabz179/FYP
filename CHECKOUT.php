<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $cardholderName = $_POST['cardholder-name'];
    $cardNumber = $_POST['card-number'];
    $expiryDate = $_POST['expiry-date'];
    $cvc = $_POST['cvc'];
    $promoCode = $_POST['promo-code'];
    $shippingOption = $_POST['shipping-option'];

    // Simple validation
    if (empty($email) || empty($phone) || empty($address) || empty($cardholderName) || empty($cardNumber) || empty($expiryDate) || empty($cvc)) {
        $errorMessage = "All fields are required!";
    } else {
        // Validate promo code
        $discountApplied = false;
        if (!empty($promoCode)) {
            if ($promoCode === "DISCOUNT10") {
                $discountApplied = true;
            } else {
                $errorMessage = "Invalid promotion code!";
            }
        }

        // If no errors, process order
        if (!isset($errorMessage)) {
            $shippingCost = ($shippingOption == "home-delivery") ? "£1.00" : "Free";
            $successMessage = "Order has been placed! Shipping: " . $shippingCost;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Bubble & Waffle</title>
    <link rel="stylesheet" href="CHECKOUT.CSS">
</head>
<body>

<header>
    <img src="IMAGES/Logo.png" alt="Bubble & Waffle Logo">
    <nav>
        <a href="HOMEPAGE.html">Homepage</a>
        <a href="CONTACT US.html">Contact Us</a>
        <a href="https://www.instagram.com/bubble_waffle21/?hl=en" target="_blank">
            <img src="IMAGES/Instagram_logo.webp" alt="Instagram Logo" style="height: 50px; vertical-align: middle; margin-right: 5px;">
        </a>
        <a href="LOGIN.php">Login</a>
    </nav>
</header>

<div class="checkout-container">
    <h1>CHECKOUT</h1>
    <p>Enjoy free collection from our store! For home delivery, there’s a charge of just £1. Choose your preferred option!</p>

    <div class="checkout-cart">
        <h2>Your Cart</h2>
        <div id="checkout-cart-items"></div> 
        <h3 id="checkout-total-price"></h3> 
        
        <h2>Promotion Code </h2>
        <form method="POST" action="checkout.php">
            <label for="promo-code">Enter Promotion Code:</label>
            <input type="text" name="promo-code" id="promo-code" placeholder="Enter code (e.g., DISCOUNT10)">
    </div>

    <div class="payment-section">
        <h2>Shipping Information</h2>
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        
        <label for="phone">Phone Number:</label>
        <input type="text" name="phone" id="phone" placeholder="Enter your phone number" required>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" placeholder="Enter billing address" required>
        
        <h2>Payment Method</h2>
        <label for="cardholder-name">Cardholder Name:</label>
        <input type="text" name="cardholder-name" id="cardholder-name" placeholder="Enter name on card" required>

        <label for="card-number">Card Number:</label>
        <input type="text" name="card-number" id="card-number" placeholder="Enter card number" required>
    
        <label for="expiry-date">Expiry Date:</label>
        <input type="text" name="expiry-date" id="expiry-date" placeholder="MM/YY" required>
    
        <label for="cvc">CVC:</label>
        <input type="text" name="cvc" id="cvc" placeholder="Enter CVC" required>
        
        <div class="shipping-options">
            <h2>Shipping Option</h2>
            <label for="collection">
                <input type="radio" name="shipping-option" value="collection" checked>
                Free Collection from Store
            </label>
            <label for="home-delivery">
                <input type="radio" name="shipping-option" value="home-delivery">
                Home Delivery (£1.00)
            </label>
        </div>
    
        <div class="button-group">
            <button type="reset">Reset</button>
            <button type="submit">Submit</button>
        </div>
        </form>

        <?php if (isset($successMessage)) : ?>
            <div class="message success">
                <?= htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errorMessage)) : ?>
            <div class="message error">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; 2025 Bubble & Waffle. All rights reserved.</p>
</footer>

<script src="CHECKOUT.JS"></script>

</body>
</html>