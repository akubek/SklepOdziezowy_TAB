<?php
class CategoryController
{
    private $categoryManager;
    private $productManager;

    public function __construct($categoryManager, $productManager)
    {
        $this->categoryManager = $categoryManager;
        $this->productManager = $productManager;
    }

    public function show($categoryId)
    {
        $categoryId = $_GET['id'] ?? null;
        if ($categoryId) {
            $currentCategory = $this->categoryManager->getCategoryById($categoryId);
            $categoryPath = $this->categoryManager->getCategoryPath($categoryId);
            $subcategories = $this->categoryManager->getSubcategories($categoryId);

            if (!empty($subcategories)) {
                $products = $this->productManager->getProductsByCategory($categoryId, 9, 'newest');
                renderView('category_list', [
                    'currentCategory' => $currentCategory,
                    'categoryPath'    => $categoryPath,
                    'subcategories'   => $subcategories,
                    'products'        => $products
                ]);
            } else {
                $sort = $_GET['sort'] ?? 'newest';
                $products = $this->productManager->getProductsByCategory($categoryId, null, $sort);
                renderView('product_list', [
                    'currentCategory' => $currentCategory,
                    'categoryPath'    => $categoryPath,
                    'products'        => $products,
                    'sort'           => $sort
                ]);
            }
        } else {
            echo "<div class='alert alert-danger'>Brak ID kategorii w adresie URL.</div>";
        }
    }
}
