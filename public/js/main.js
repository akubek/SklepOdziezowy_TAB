document.addEventListener('DOMContentLoaded', () => {
    
    console.log("Hello world");

    const cartBtn = document.querySelector('.add-to-cart');
    if (cartBtn) {
        cartBtn.addEventListener('click', () => {
            alert("Dodano do koszyka!");
        });
    }

});
