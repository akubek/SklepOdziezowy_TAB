<?php
class CategoryController {
    private $categoryManager;
    private $productManager;

    public function __construct($categoryManager, $productManager) {
        $this->categoryManager = $categoryManager;
        $this->productManager = $productManager;
    }

    public function show($categoryId) {
        $categoryId = $_GET['id'] ?? null;
        if ($categoryId) {
            $currentCategory = $this->categoryManager->getCategoryById($categoryId);
            $categoryPath = $this->categoryManager->getCategoryPath($categoryId);
            $subcategories = $this->categoryManager->getSubcategories($categoryId);
            
            require_once '../views/partials/breadcrumb.php';

            if (!empty($subcategories)) {
                $products = $this->productManager->getProductsByCategory($categoryId, 9, 'newest');
                require_once '../views/category_list.php';
            } else {
                $sort = $_GET['sort'] ?? 'newest';
                $products = $this->productManager->getProductsByCategory($categoryId, null, $sort);
                require_once '../views/product_list.php';
            }
        } else {
            echo "<div class='alert alert-danger'>Brak ID kategorii w adresie URL.</div>";
        }    
    }
}
?>
