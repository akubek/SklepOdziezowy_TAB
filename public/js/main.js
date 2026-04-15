function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// update number of items displayed on the cart icon/badge
function updateCartBadge() {
    const cartCookie = getCookie('cart');
    let totalItems = 0;
    
    if (cartCookie) {
        try {
            const cart = JSON.parse(cartCookie);
            totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
        } catch (e) {
            console.error("Błąd parsowania koszyka", e);
        }
    }

    const badge = document.querySelector('a[href="index.php?page=cart"] .badge');
    if (badge) {
        badge.innerText = totalItems;
    }
}

document.addEventListener('DOMContentLoaded', () => {    
    // after reloading page update the cart badge 
    updateCartBadge();
});
