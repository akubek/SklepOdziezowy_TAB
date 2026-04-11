<?php
require_once '../src/Database.php';
require_once '../src/ProductManager.php';
require_once '../src/CategoryManager.php';

$pdo = Database::getConnection();

$productManager = new ProductManager($pdo);
$categoryManager = new CategoryManager($pdo);

$page = $_GET['page'] ?? 'home';

require_once '../views/partials/header.php';

switch ($page) {
    case 'home':
        // Na stronie głównej ładujemy TYLKO główne kategorie (Level 1)
        $categories = $categoryManager->getRootCategories();
        require_once '../views/home.php';
        break;

    case 'category':
        // Użytkownik kliknął w kategorię
        $categoryId = $_GET['id'] ?? null;
        
        if ($categoryId) {
            $currentCategory = $categoryManager->getCategoryById($categoryId);
            $subcategories = $categoryManager->getSubcategories($categoryId);
            
            // Jeśli kategoria ma podkategorie (Level 2), wyświetlamy listę podkategorii
            // Jeśli nie ma (Level 3 - np. Kurtki), to znak, że trzeba załadować produkty!
            if (!empty($subcategories)) {
                require_once '../views/category_list.php';
            } else {
                // TU PÓŹNIEJ DODAMY POBIERANIE PRODUKTÓW
                echo "<h2>" . htmlspecialchars($currentCategory['name']) . "</h2>";
                echo "<p>Tutaj niedługo wyświetlimy kafelki z produktami!</p>";
            }
        } else {
            echo "Brak ID kategorii.";
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
