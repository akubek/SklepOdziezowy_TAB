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
    'add_review'      => fn($c) => $c['reviewController']($c)->add(),
    'delete_review'   => fn($c) => $c['reviewController']($c)->delete(),

    // Obsługa błędów (przez ErrorController)
    '403' => fn($c) => $c['errorController']($c)->forbidden(),
    '404' => fn($c) => $c['errorController']($c)->notFound(),
    '500' => fn($c) => $c['errorController']($c)->internalError()
];
