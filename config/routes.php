<?php
return [
    'home' => function() {
        require_once '../views/home.php';
    },
    'category' => function() use ($categoryManager, $productManager) {
        $controller = new CategoryController($categoryManager, $productManager);
        $controller->show($_GET['id'] ?? null);
    },
    'cart' => function() use ($productManager) {
        $controller = new CartController($productManager);
        $controller->show();
    },
    'product' => function() use ($productManager, $reviewManager) {
        $controller = new ProductController($productManager, $reviewManager);
        $controller->show($_GET['id'] ?? null);
    },
    'login' => function() use ($pdo) {
        $controller = new AuthController($pdo);
        $controller->showLogin();
    },
    'logout' => function() use ($pdo) {
        $controller = new AuthController($pdo);
        $controller->logout();
    },
    'register' => function() use ($pdo) {
        $controller = new AuthController($pdo);
        $controller->showRegister();
    },
    'add_review' => function() use ($reviewManager) {
        $controller = new ReviewController($reviewManager);
        $controller->add();
    },
    'delete_review' => function() use ($reviewManager) {
        $controller = new ReviewController($reviewManager);
        $controller->delete();
    },
    'profile' => function() use ($pdo) {
        $authController = new AuthController($pdo);
        $authController->showProfile();
    },
    'change-password' => function() use ($pdo) {
        $authController = new AuthController($pdo);
        $authController->changePassword();
    },
    '404' => function() {
        echo "
        <div class='text-center py-5 my-5'>
            <h1 class='display-1 fw-bold text-muted'>404</h1>
            <h2 class='mb-4'>Oops! Strona nie znaleziona.</h2>
            <p class='lead mb-4'>Wygląda na to, że zgubiłeś się w naszym sklepie.</p>
            <a href='index.php?page=home' class='btn btn-primary btn-lg'>Wróć na stronę główną</a>
        </div>
        ";
    },
    '500' => function() {
        echo "
        <div class='text-center py-5 my-5'>
            <h1 class='display-1 fw-bold text-danger'>500</h1>
            <h2 class='mb-4'>Problem techniczny</h2>
            <p class='lead mb-4'>Spróbuj ponownie później.</p>
        </div>
        ";
    }
];
