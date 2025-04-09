document.addEventListener('DOMContentLoaded', function () {
    const checkoutItemsContainer = document.getElementById('checkout-cart-items');
    const checkoutTotalElement = document.getElementById('checkout-total-price');

    const cart = sessionStorage.getItem('cart') ? JSON.parse(sessionStorage.getItem('cart')) : [];

    if (cart.length === 0) {
        checkoutItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
        checkoutTotalElement.textContent = '';
    } else {
        let total = 0;
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            const itemHTML = `
                <div class="checkout-item">
                    <img src="${item.image}" alt="${item.name}" class="checkout-item-image">
                    <h3>${item.name}</h3>
                    <p>Quantity: ${item.quantity}</p>
                    <p>Item Total: £${itemTotal.toFixed(2)}</p>
                </div>
            `;

            checkoutItemsContainer.innerHTML += itemHTML;
        });

        checkoutTotalElement.textContent = `Total: £${total.toFixed(2)}`;
    }
});