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

    'profile'                   => fn($c) => $c['profileController']($c)->index(),
    'profile_orders'            => fn($c) => $c['profileController']($c)->orders(),
    'profile_order_details'     => fn($c) => $c['profileController']($c)->orderDetails(),
    'profile_reviews'           => fn($c) => $c['profileController']($c)->reviews(),
    'profile_review_delete'     => fn($c) => $c['profileController']($c)->deleteReview(),
    'profile_addresses'         => fn($c) => $c['profileController']($c)->addresses(),
    'profile_address_add'       => fn($c) => $c['profileController']($c)->addAddress(),
    'profile_address_delete'    => fn($c) => $c['profileController']($c)->deleteAddress(),
    'profile_settings'          => fn($c) => $c['profileController']($c)->settings(),
    'profile_update'            => fn($c) => $c['profileController']($c)->update(),
    'password_change'           => fn($c) => $c['profileController']($c)->changePassword(),

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
    '500' => fn($c) => $c['errorController']($c)->internalError()
];
