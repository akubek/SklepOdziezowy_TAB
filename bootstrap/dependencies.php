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
    'pdo' => function($c) {
        // Korzystamy z Twojej klasy DatabaseConnection
        return DatabaseConnection::getConnection(); //singleton, careful if changed
    },

    // Managers, single instance
    'categoryManager' => function($c) {
        //singleton isnstance is optimal
        static $instance;
        if ($instance === null) {
            $instance = new CategoryManager($c['pdo']($c));
        }
        return $instance;
    },

    'productManager' => function($c) {
        static $instance;
        if ($instance === null) {
            $instance = new ProductManager($c['pdo']($c));
        }
        return $instance;
    },

    'reviewManager' => function($c) {
        static $instance;
        if ($instance === null) {
            $instance = new ReviewManager($c['pdo']($c));
        }
        return $instance;
    },
];
