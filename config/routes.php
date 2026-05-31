<?php
// config/routes.php
return [
    'home'      => fn($c) => $c['homeController']($c)->index(),

    'category'  => fn($c) => $c['categoryController']($c)->show($_GET['id'] ?? null),

    'cart'      => fn($c) => $c['cartController']($c)->show(),

    'product'   => fn($c) => $c['productController']($c)->show($_GET['id'] ?? null),
    'search'    => fn($c) => $c['productController']($c)->search(),

    // Grupa Auth
    'login'             => fn($c) => $c['authController']($c)->showLogin(),
    'logout'            => fn($c) => $c['authController']($c)->logout(),
    'register'          => fn($c) => $c['authController']($c)->showRegister(),
    'profile'           => fn($c) => $c['authController']($c)->showProfile(),
    'change-password'   => fn($c) => $c['authController']($c)->changePassword(),

    // Recenzje
    'add_review'        => fn($c) => $c['reviewController']($c)->add(),
    'delete_review'     => fn($c) => $c['reviewController']($c)->delete(),

    // Checkout
    'checkout_start'    => fn($c) => $c['checkoutController']($c)->start(),
    'checkout_form'     => fn($c) => $c['checkoutController']($c)->handleCheckout(),
    'checkout_success'  => fn($c) => $c['checkoutController']($c)->showSuccess(),

    // Obsługa błędów (przez ErrorController)
    '403' => fn($c) => $c['errorController']($c)->forbidden(),
    '404' => fn($c) => $c['errorController']($c)->notFound(),
    '409' => fn($c) => $c['errorController']($c)->conflict(),
    '500' => fn($c) => $c['errorController']($c)->internalError(),

    // Panel Administracyjny 
    'admin_orders'        => fn($c) => $c['orderFulfillmentController']($c)->index(),
    'admin_order_details' => fn($c) => $c['orderFulfillmentController']($c)->show(),
    'admin_order_update'  => fn($c) => $c['orderFulfillmentController']($c)->updateStatus(),

    // --- Panel Managera ---
    'admin_inventory' => fn($c) => $c['inventoryController']($c)->index(),
    
    // CRUD Kategorii
    'admin_category_add'    => fn($c) => $c['inventoryController']($c)->showCategoryForm(),
    'admin_category_edit'   => fn($c) => $c['inventoryController']($c)->showCategoryForm(),
    'admin_category_save'   => fn($c) => $c['inventoryController']($c)->saveCategory(),
    'admin_category_delete' => fn($c) => $c['inventoryController']($c)->deleteCategory(),

    // CRUD Produktów
    'admin_product_add'    => fn($c) => $c['inventoryController']($c)->showProductForm(),
    'admin_product_edit'   => fn($c) => $c['inventoryController']($c)->showProductForm(),
    'admin_product_save'   => fn($c) => $c['inventoryController']($c)->saveProduct(),
    'admin_product_delete' => fn($c) => $c['inventoryController']($c)->deleteProduct(),

    // CRUD Wariantów
    'admin_variants'       => fn($c) => $c['inventoryController']($c)->showVariantsList(),
    'admin_variant_add'    => fn($c) => $c['inventoryController']($c)->showVariantForm(),
    'admin_variant_edit'   => fn($c) => $c['inventoryController']($c)->showVariantForm(),
    'admin_variant_save'   => fn($c) => $c['inventoryController']($c)->saveVariant(),
    'admin_variant_delete' => fn($c) => $c['inventoryController']($c)->deleteVariant()
];
