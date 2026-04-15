document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('product-container');
    
    // check if this is the product-container page 
    if (!container) return;

    const variants = JSON.parse(container.dataset.variants);
    const selector = document.getElementById('variant-selector');
    const priceTag = document.getElementById('current-price');
    const imageTag = document.getElementById('main-product-image');
    const stockTag = document.getElementById('stock-info');
    const addToCartBtn = document.getElementById('add-to-cart-btn');

    // handle variant change (image, price modifier, inventory) 
    selector.addEventListener('change', (e) => {
        const variantId = e.target.value;
        const variant = variants.find(v => v.id == variantId);
        
        if (variant) {
            const basePrice = parseFloat(container.dataset.basePrice || 0);
            priceTag.innerText = (basePrice + parseFloat(variant.price_modifier)).toFixed(2) + ' zł';
            
            const images = JSON.parse(variant.images);
            if (images.length > 0) imageTag.src = images[0];
            
            stockTag.innerText = `Dostępność: ${variant.stock_quantity} szt.`;
            addToCartBtn.disabled = variant.stock_quantity <= 0;
        }
    });
    
    // load first variant in the list 
    selector.dispatchEvent(new Event('change'));

    // add varinat to cart 
    addToCartBtn.addEventListener('click', () => {
        const variantId = selector.value;
        if (!variantId) return;

        let cart = {};
        const currentCookie = getCookie('cart'); 
        
        if (currentCookie) {
            try {
                cart = JSON.parse(currentCookie);
            } catch (e) { console.error("Błąd odczytu koszyka", e); }
        }

        if (cart[variantId]) {
            cart[variantId] += 1;
        } else {
            cart[variantId] = 1;
        }

        setCookie('cart', JSON.stringify(cart), 7); 
        updateCartBadge(); 
        
        // button effect on adding item 
        const originalText = addToCartBtn.innerText;
        addToCartBtn.innerText = "Dodano!";
        addToCartBtn.classList.add('btn-success');
        addToCartBtn.classList.remove('btn-primary');
        
        setTimeout(() => {
            addToCartBtn.innerText = originalText;
            addToCartBtn.classList.add('btn-primary');
            addToCartBtn.classList.remove('btn-success');
        }, 500);
    });
});
