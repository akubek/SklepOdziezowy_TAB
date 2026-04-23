<?php
// config/routes.php
return [
    'home' => function($c) {
        $c['homeController']($c)->index();
    },

    'category' => function($c) {
        $c['categoryController']($c)->show($_GET['id'] ?? null);
    },

    'cart' => function($c) {
        $c['cartController']($c)->show();
    },

    'product' => function($c) {
        $c['productController']($c)->show($_GET['id'] ?? null);
    },

    // Grupa Auth
    'login'           => function($c) { $c['authController']($c)->showLogin(); },
    'logout'          => function($c) { $c['authController']($c)->logout(); },
    'register'        => function($c) { $c['authController']($c)->showRegister(); },
    'profile'         => function($c) { $c['authController']($c)->showProfile(); },
    'change-password' => function($c) { $c['authController']($c)->changePassword(); },

    // Recenzje
    'add_review'      => function($c) { $c['reviewController']($c)->add(); },
    'delete_review'   => function($c) { $c['reviewController']($c)->delete(); },

    // Obsługa błędów (przez ErrorController)
    '404' => function($c) { $c['errorController']($c)->notFound(); },
    '500' => function($c) { $c['errorController']($c)->internalError(); }
];
