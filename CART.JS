const cartItemsContainer = document.getElementById('cart-items');
const totalPriceElement = document.getElementById('total-price');
const clearCartButton = document.getElementById('clear-cart-button');

// Initialize cart from session storage (temporary cart)
let cart = sessionStorage.getItem('cart') ? JSON.parse(sessionStorage.getItem('cart')) : [];

// Function to display items in the cart
function displayCartItems() {
    cartItemsContainer.innerHTML = ''; 
    let totalPrice = 0;

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>'; 
    } else {
        cart.forEach((item, index) => {
            const itemTotalPrice = item.price * item.quantity;
            cartItemsContainer.innerHTML += `
                <div class="cart-item">
                    <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                    <div class="cart-item-details">
                        <h2>${item.name}</h2>
                        <p>Price: £${item.price.toFixed(2)}</p>
                        <p>Quantity: ${item.quantity}</p>
                        <p>Total: £${itemTotalPrice.toFixed(2)}</p>
                        <button onclick="removeFromCart(${index})">Remove</button>
                    </div>
                </div>
            `;
            totalPrice += itemTotalPrice;
        });

        totalPriceElement.textContent = `Total: £${totalPrice.toFixed(2)}`;
    }
}

// Function to add an item to the cart
function addToCart(event) {
    const button = event.target;
    const name = button.getAttribute('data-name');
    const price = parseFloat(button.getAttribute('data-price'));
    const image = button.getAttribute('data-image');
    const quantity = parseInt(button.previousElementSibling.value);

    if (!name || isNaN(price) || !image || isNaN(quantity) || quantity < 1) {
        console.error("Invalid product data.");
        return;
    }

    // Check if the item already exists
    const existingItem = cart.find(item => item.name === name);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        cart.push({ name, price, image, quantity });
    }

    // Store cart in sessionStorage (resets when browser closes)
    sessionStorage.setItem('cart', JSON.stringify(cart));

    alert(`${name} has been added to your cart.`);
    window.location.href = 'CART.HTML';
}

// Function to remove an item from the cart
function removeFromCart(index) {
    cart.splice(index, 1);
    sessionStorage.setItem('cart', JSON.stringify(cart));
    displayCartItems();
}

// Function to proceed to checkout
function proceedToCheckout() {
    if (cart.length === 0) {
        alert('Your cart is empty! Add some items to proceed.');
    } else {
        sessionStorage.setItem('cart', JSON.stringify(cart));
        window.location.href = 'CHECKOUT.php';
    }
}

// Function to clear the cart
function clearCart() {
    cart = [];
    sessionStorage.removeItem('cart');
    displayCartItems();
    alert('Your cart has been cleared.');
}

// Event listeners
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', addToCart);
});

if (clearCartButton) {
    clearCartButton.addEventListener('click', clearCart);
}

// Display cart items on page load
if (cartItemsContainer) {
    displayCartItems();
}