<?php
ob_start();
session_start();
require_once '../src/Database.php';
require_once '../src/ProductManager.php';
require_once '../src/CategoryManager.php';
require_once '../src/controllers/CategoryController.php';
require_once '../src/controllers/ProductController.php';
require_once '../src/controllers/CartController.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/helpers.php';

$pdo = Database::getConnection();

$productManager = new ProductManager($pdo);
$categoryManager = new CategoryManager($pdo);

$page = $_GET['page'] ?? 'home';

$allowedPages = ['home', 'category', 'cart', 'product', 'login', 'register', 'logout'];

if (!in_array($page, $allowedPages)) {
    http_response_code(404);
    $page = '404';
}

$rootCategories = $categoryManager->getRootCategories();
$mainCategories = $categoryManager->getSubcategories($rootCategories[0]['id']);

require_once '../views/partials/header.php';

switch ($page) {
    case 'home':
        require_once '../views/home.php';
        break;

    case 'category':
        $controller = new CategoryController($categoryManager,$productManager);
        $controller->show($_GET['id'] ?? null);
        break;

    case 'cart':
        $controller = new CartController($productManager);
        $controller->show();
        break;

    case 'product':
        $controller = new ProductController($productManager);
        $controller->show($_GET['id'] ?? null);
        break;

    case 'login':
        $authController = new AuthController($pdo);
        $authController->showLogin();
        break;

    case 'logout':
        $authController = new AuthController($pdo);
        $authController->logout();
        break;

    case 'register':
        $authController = new AuthController($pdo);
        $authController->showRegister();
        break;

    case '404':
        echo "
        <div class='text-center py-5 my-5'>
            <h1 class='display-1 fw-bold text-muted'>404</h1>
            <h2 class='mb-4'>Oops! Strona nie znaleziona.</h2>
            <p class='lead mb-4'>Wygląda na to, że zgubiłeś się w naszym sklepie.</p>
            <a href='index.php?page=home' class='btn btn-primary btn-lg'>Wróć na stronę główną</a>
        </div>
        ";
        break;

    default:
        break;
}

require_once '../views/partials/footer.php';
?>
