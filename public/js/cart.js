document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('cart-table');
    if (!table) return;

    function updateTotals() {
        let total = 0;
        document.querySelectorAll('tbody tr').forEach(row => {
            const qty = parseInt(row.querySelector('.qty-input').value);
            const price = parseFloat(row.dataset.price);
            const subtotal = qty * price;
            row.querySelector('.subtotal').innerText = subtotal.toFixed(2).replace('.', ',') + ' zł';
            total += subtotal;
        });
        document.getElementById('cart-total').innerText = total.toFixed(2).replace('.', ',') + ' zł';
        document.getElementById('cart-grand-total').innerText = total.toFixed(2).replace('.', ',') + ' zł';
        updateCartBadge(); // funkcja z main.js
    }

    function syncCookie() {
        let cart = {};
        document.querySelectorAll('tbody tr').forEach(row => {
            cart[row.dataset.variantId] = parseInt(row.querySelector('.qty-input').value);
        });
        setCookie('cart', JSON.stringify(cart), 7);
    }

    table.addEventListener('click', (e) => {
        const row = e.target.closest('tr');
        if (!row) return;

        const input = row.querySelector('.qty-input');
        const stock = parseInt(row.dataset.stock);
        let qty = parseInt(input.value);

        if (e.target.classList.contains('btn-plus') && qty < stock) {
            input.value = ++qty;
        } else if (e.target.classList.contains('btn-minus') && qty > 1) {
            input.value = --qty;
        } else if (e.target.classList.contains('btn-remove')) {
            row.remove();
            if (document.querySelectorAll('tbody tr').length === 0) location.reload();
        } else {
            return;
        }

        syncCookie();
        updateTotals();
    });

    document.getElementById('clear-cart').addEventListener('click', () => {
        setCookie('cart', '', -1);
        location.reload();
    });
});
