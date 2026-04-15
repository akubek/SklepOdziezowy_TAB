<?php
class ProductController {
    private $productManager;

    public function __construct($productManager) {
        $this->productManager = $productManager;
    }

    public function show($productId) {
        if ($productId) {
            // load product and its variants 
            $product = $this->productManager->getProductWithVariants($productId);
            
            if ($product) {
                // if product exists
                require_once '../views/product_details.php';
            } else {
                echo "<div class='alert alert-warning text-center mt-5'>Nie znaleziono takiego produktu.</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center mt-5'>Brak ID produktu w adresie URL.</div>";
        }
    }
}
?>
