<?php
// public/index.php
require_once __DIR__ . '/../bootstrap/init.php';
require_once BASE_PATH . '/src/DatabaseConnection.php';
require_once BASE_PATH . '/src/ProductManager.php';
require_once BASE_PATH . '/src/CategoryManager.php';
require_once BASE_PATH . '/src/ReviewManager.php';
require_once BASE_PATH . '/src/controllers/CategoryController.php';
require_once BASE_PATH . '/src/controllers/ProductController.php';
require_once BASE_PATH . '/src/controllers/CartController.php';
require_once BASE_PATH . '/src/controllers/AuthController.php';
require_once BASE_PATH . '/src/controllers/ReviewController.php';
require_once BASE_PATH . '/src/helpers.php';

$pdo = null;
$categoryManager = null;
$productManager = null;
$reviewManager = null;
$rootCategories = [];
$mainCategories = [];

// global view logic
$page = $_GET['page'] ?? 'home';

try {
    // 2. Próba połączenia i inicjalizacja managerów
    $pdo = DatabaseConnection::getConnection();
    
    $categoryManager = new CategoryManager($pdo);
    $productManager = new ProductManager($pdo);
    $reviewManager = new ReviewManager($pdo);

    // 3. Dane do nagłówka (wymagają bazy!)
    $rootCategories = $categoryManager->getRootCategories();
    $firstRootCatId = !empty($rootCategories) ? $rootCategories[0]['id'] : null;
    $mainCategories = $firstRootCatId ? $categoryManager->getSubcategories($firstRootCatId) : [];

} catch (Exception $e) {
    error_log("CRITICAL ERROR: " . get_class($e) . ": " . $e->getMessage());
    http_response_code(500);
    $page = '500';
}

$routes = require_once BASE_PATH . "/config/routes.php";

if ($page !== '500' && !array_key_exists($page,$routes)) {
    http_response_code(404);
    $page = '404';
}

if ($page === '500') {
    $routes['500']();
} else {
     // render page
    require_once BASE_PATH . '/views/partials/header.php';
    $routes[$page]();
    require_once BASE_PATH . '/views/partials/footer.php';
}
?>
