<?php
class CartController {
    private $productManager;

    public function __construct($productManager) {
        $this->productManager = $productManager;
    }

    public function show() {
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
        $items = [];
        $totalPrice = 0;

        if (!empty($cart)) {
            $variantIds = array_keys($cart);
            $variants = $this->productManager->getVariantsWithProductInfo($variantIds);
            
            foreach ($variants as $v) {
                $qty = $cart[$v['variant_id']];
                $unitPrice = $v['base_price'] + $v['price_modifier'];
                $subtotal = $unitPrice * $qty;
                
                $items[] = [
                    'variant_id' => $v['variant_id'],
                    'name' => $v['product_name'],
                    'attributes' => json_decode($v['attributes'], true),
                    'image' => json_decode($v['images'], true)[0] ?? 'https://placehold.co/100x100',
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                    'stock' => $v['stock_quantity']
                ];
                $totalPrice += $subtotal;
            }
        }
        require_once '../views/cart.php';
    }
}
