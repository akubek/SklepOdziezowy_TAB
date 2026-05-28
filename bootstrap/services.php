<?php
// bootstrap/dependencies.php

/**
 * Dependency Injection Container Definitions
 *
 * Returns an associative array of "recipes" for application services and managers.
 * Each service is wrapped in a closure to enable lazy loading (instantiation on demand).
 *
 * @param array $c The container itself, passed to each closure to resolve nested dependencies.
 * @return array
 */

return [
    // PDO instance - connection with database
    'pdo' => function ($c) {
        require_once BASE_PATH . '/src/core/DatabaseConnection.php';
        return DatabaseConnection::getConnection(); //singleton, careful if changed
    },

    // Managers

    'authManager' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/managers/AuthManager.php';
            $instance = new AuthManager($c['userManager']($c));
        }
        return $instance;
    },

    'categoryManager' => function ($c) {
        //singleton isnstance is optimal
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/managers/CategoryManager.php';
            $instance = new CategoryManager($c['pdo']($c));
        }
        return $instance;
    },

    'cartManager' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/managers/CartManager.php';
            $instance = new CartManager($c['productManager']($c));
        }
        return $instance;
    },

    'orderManager' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/managers/OrderManager.php';
            $instance = new OrderManager($c['pdo']($c));
        }
        return $instance;
    },

    'productManager' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/managers/ProductManager.php';
            $instance = new ProductManager($c['pdo']($c));
        }
        return $instance;
    },

    'reviewManager' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/managers/ReviewManager.php';
            $instance = new ReviewManager($c['pdo']($c));
        }
        return $instance;
    },

    'userManager' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/managers/UserManager.php';
            $instance = new UserManager($c['pdo']($c));
        }
        return $instance;
    },

    'orderFulfillmentManager' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/managers/OrderFulfillmentManager.php';
            $instance = new OrderFulfillmentManager($c['pdo']($c));
        }
        return $instance;
    },

    //Controllers
    'authController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/AuthController.php';
            $instance = new AuthController($c['authManager']($c), $c['userManager']($c));
        }
        return $instance;
    },

    'cartController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/CartController.php';
            $instance = new CartController($c['cartManager']($c));
        }
        return $instance;
    },

    'checkoutController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/CheckoutController.php';
            $instance = new CheckoutController($c['orderManager']($c), $c['cartManager']($c), $c['userManager']($c));
        }
        return $instance;
    },

    'categoryController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/CategoryController.php';
            $instance = new CategoryController($c['categoryManager']($c), $c['productManager']($c));
        }
        return $instance;
    },

    'homeController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/HomeController.php';
            $instance = new HomeController($c['categoryManager']($c), $c['productManager']($c));
        }
        return $instance;
    },

    'productController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/ProductController.php';
            $instance = new ProductController($c['productManager']($c), $c['reviewManager']($c), $c['categoryManager']($c));
        }
        return $instance;
    },

    'profileController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/ProfileController.php';
            $instance = new ProfileController($c['userManager']($c), $c['orderManager']($c), $c['reviewManager']($c));
        }
        return $instance;
    },

    'reviewController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/ReviewController.php';
            $instance = new ReviewController($c['reviewManager']($c));
        }
        return $instance;
    },

    'errorController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/ErrorController.php';
            $instance = new ErrorController();
        }
        return $instance;
    },

    'orderFulfillmentController' => function ($c) {
        static $instance;
        if ($instance === null) {
            require_once BASE_PATH . '/src/controllers/OrderFulfillmentController.php';
            $instance = new OrderFulfillmentController($c['orderFulfillmentManager']($c));
        }
        return $instance;
    }
];
