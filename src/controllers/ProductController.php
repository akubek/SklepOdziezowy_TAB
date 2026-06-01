<?php
class ProductController
{
    private $productManager;
    private $reviewManager;
    private $categoryManager;

    // Odbieramy dwa menedżery w konstruktorze
    public function __construct($productManager, $reviewManager, $categoryManager)
    {
        $this->productManager = $productManager;
        $this->reviewManager = $reviewManager;
        $this->categoryManager = $categoryManager;
    }

    public function show($productId)
    {
        if ($productId) {
            $product = $this->productManager->getProductWithVariants($productId);

            if ($product) {
                $reviews = $this->reviewManager->getReviewsByProductId($productId);
                $ratingData = $this->reviewManager->getAverageRating($productId);
                $productAttrs = json_decode($product['attributes'], true);
                $colorsDict = require BASE_PATH . '/config/colors.php';

                //create breadcrumb
                $categoryPath = $this->categoryManager->getCategoryPath($product['category_id']);
                $breadcrumbs = [];
                $breadcrumbs[] = ['name' => 'Strona Główna', 'url' => 'index.php?page=home'];
                if (!empty($categoryPath)) {
                    foreach ($categoryPath as $step) {
                        $breadcrumbs[] = [
                            'name' => $step['name'],
                            'url'  => 'index.php?page=category&id=' . $step['id']
                        ];
                    }
                }
                $breadcrumbs[] = ['name' => $product['name'], 'url' => null];

                //Sprawdzenie czy zalogowany użytkownik dodał już opinię
                $hasReviewed = false;
                if (isset($_SESSION['user_id'])) {
                    $hasReviewed = $this->reviewManager->hasUserReviewedProduct($_SESSION['user_id'], $productId);
                }
                renderView('product_details', [
                    'product' => $product,
                    'productAttrs' => $productAttrs,
                    'reviews' => $reviews,
                    'ratingData' => $ratingData,
                    'hasReviewed' => $hasReviewed,
                    'breadcrumbs' => $breadcrumbs,
                    'colorsDict' => $colorsDict
                ]);
            } else {
                echo "<div class='alert alert-warning text-center mt-5'>Nie znaleziono takiego produktu.</div>";
            }
        } else {
            echo "<div class='alert alert-danger text-center mt-5'>Brak ID produktu w adresie URL.</div>";
        }
    }

    public function search(?string $query = null, ?string $sort = null)
    {
        $q = $query ?: trim($_GET['q']) ?? '';
        $s = $sort ?: trim($_GET['sort']) ?? 'newest';

        if (empty($q)) {
            renderView('search_results', [
                'products' => [],
                'searchQuery' => '',
                'title' => 'Wyniki wyszukiwania'
            ]);
            return;
        }

        $products = $this->productManager->searchProducts($q, $s);

        renderView('search_results', [
            'products' => $products,
            'searchQuery' => $q,
            'sort' => $s,
            'title' => 'Wyniki dla: ' . e($q)
        ]);
    }
}
