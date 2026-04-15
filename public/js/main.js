document.addEventListener('DOMContentLoaded', () => {
    
    console.log("Hello world");

    const cartBtn = document.querySelector('.add-to-cart');
    if (cartBtn) {
        cartBtn.addEventListener('click', () => {
            alert("Dodano do koszyka!");
        });
    }

});

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('product-container');
    if (!container) return;

    const variants = JSON.parse(container.dataset.variants);
    const selector = document.getElementById('variant-selector');
    const priceTag = document.getElementById('current-price');
    const imageTag = document.getElementById('main-product-image');
    const stockTag = document.getElementById('stock-info');

    selector.addEventListener('change', (e) => {
        const selectedId = e.target.value;
        const variant = variants.find(v => v.id == selectedId);

        if (variant) {
            // calculate modified price
            const basePrice = parseFloat(container.dataset.basePrice || 0);
            const finalPrice = basePrice + parseFloat(variant.price_modifier);
            priceTag.innerText = finalPrice.toFixed(2) + ' zł';

            // get variant image 
            const images = JSON.parse(variant.images);
            if (images.length > 0) {
                imageTag.src = images[0];
            }

            // display inventory stock for variant 
            stockTag.innerText = `Dostępność: ${variant.stock_quantity} szt.`;
            document.getElementById('add-to-cart-btn').disabled = variant.stock_quantity <= 0;
        }
    });
    
    // Trigger when product is loaded, to load first variant 
    selector.dispatchEvent(new Event('change'));
});
