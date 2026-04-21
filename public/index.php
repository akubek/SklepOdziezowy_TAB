<?php
ob_start();
session_start();

require_once '../src/Database.php';
require_once '../src/ProductManager.php';
require_once '../src/CategoryManager.php';
require_once '../src/ReviewManager.php';
require_once '../src/controllers/CategoryController.php';
require_once '../src/controllers/ProductController.php';
require_once '../src/controllers/CartController.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/ReviewController.php';
require_once '../src/helpers.php';

// database connection
$pdo = Database::getConnection();

// managers 
$productManager = new ProductManager($pdo);
$categoryManager = new CategoryManager($pdo);
$reviewManager = new ReviewManager($pdo);

// load routes table from config
$routes = require_once "../config/routes.php";

// global view logic
$page = $_GET['page'] ?? 'home';

if (!array_key_exists($page,$routes)) {
    http_response_code(404);
    $page = '404';
}

// load categories - displayed in header
$rootCategories = $categoryManager->getRootCategories();
$firstRootCatId = !empty($rootCategories) ? $rootCategories[0]['id'] : null;
$mainCategories = $firstRootCatId ? $categoryManager->getSubcategories($firstRootCatId) : [];

// render page
require_once '../views/partials/header.php';

$routes[$page]();

require_once '../views/partials/footer.php';
?>
