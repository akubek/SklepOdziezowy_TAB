<?php
class ProductController
{
    private $productManager;
    private $reviewManager;

    // Odbieramy dwa menedżery w konstruktorze
    public function __construct($productManager, $reviewManager)
    {
        $this->productManager = $productManager;
        $this->reviewManager = $reviewManager;
    }

    public function show($productId)
    {
        if ($productId) {
            $product = $this->productManager->getProductWithVariants($productId);

            if ($product) {
                $reviews = $this->reviewManager->getReviewsByProductId($productId);
                $ratingData = $this->reviewManager->getAverageRating($productId);

                //Sprawdzenie czy zalogowany użytkownik dodał już opinię
                $hasReviewed = false;
                if (isset($_SESSION['user_id'])) {
                    $hasReviewed = $this->reviewManager->hasUserReviewedProduct($_SESSION['user_id'], $productId);
                }
                renderView('product_details', [
                    'product' => $product,
                    'reviews' => $reviews,
                    'ratingData' => $ratingData,
                    'hasReviewed' => $hasReviewed
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

        $products = $this->productManager->searchProducts($q);

        renderView('search_results', [
            'products' => $products,
            'searchQuery' => $q,
            'title' => 'Wyniki dla: ' . e($q)
        ]);
    }
}
