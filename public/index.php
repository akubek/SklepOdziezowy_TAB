<?php
require_once '../src/Database.php';
require_once '../src/ProductManager.php';
require_once '../src/CategoryManager.php';

$pdo = Database::getConnection();

$productManager = new ProductManager($pdo);
$categoryManager = new CategoryManager($pdo);

$page = $_GET['page'] ?? 'home';

$rootCategories = $categoryManager->getRootCategories();
$mainCategories = $categoryManager->getSubcategories($rootCategories[0]['id']);

require_once '../views/partials/header.php';

switch ($page) {
    case 'home':
        require_once '../views/home.php';
        break;

    case 'category':
        $categoryId = $_GET['id'] ?? null;
        
        if ($categoryId) {
            $currentCategory = $categoryManager->getCategoryById($categoryId);
            $subcategories = $categoryManager->getSubcategories($categoryId);
            
            $products = $productManager->getProductsByCategory($categoryId);

            if (!empty($subcategories)) {
                require_once '../views/category_list.php';
            } else {
                require_once '../views/product_list.php';
            }
        } else {
            echo "No category ID.";
        }
        break;

    case 'cart':
        require_once '../views/cart.php';
        break;

    default:
        http_response_code(404);
        echo "<h2>Błąd 404</h2>";
        break;
}

require_once '../views/partials/footer.php';
?>
