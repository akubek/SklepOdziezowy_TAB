<?php
class ReviewController {
    private $reviewManager;

    public function __construct($reviewManager) {
        $this->reviewManager = $reviewManager;
    }

    public function add() {
        // Sprawdzamy, czy żądanie to POST i czy użytkownik jest zalogowany
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            
            // Pobieramy dane z formularza
            $productId = $_POST['product_id'] ?? null;
            $userId = $_SESSION['user_id'];
            $rating = (int)($_POST['rating'] ?? 5);
            $comment = trim($_POST['comment'] ?? '');

            // Prosta walidacja
            if ($productId && $rating >= 1 && $rating <= 5 && !empty($comment)) {
                
                if ($this->reviewManager->hasUserReviewedProduct($userId, $productId)) {
                    header("Location: index.php?page=product&id=" . $productId . "#opinie");
                exit;
                }
                // Zapisujemy opinię przez naszego Managera
                $this->reviewManager->addReview($productId, $userId, $rating, $comment);
                
                // Przekierowujemy z powrotem do produktu i dodajemy w URL flagę sukcesu
                header("Location: index.php?page=product&id=" . $productId . "&status=review_added#reviews");
                exit;
            }
        }
        
        // Jeśli coś poszło nie tak (np. wejście z palca w URL), wracamy na stronę główną
        header("Location: index.php");
        exit;
    }
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $reviewId = $_POST['review_id'] ?? null;
            $productId = $_POST['product_id'] ?? null;
            $userId = $_SESSION['user_id'];

            if ($reviewId && $productId) {
                $this->reviewManager->deleteReview($reviewId, $userId);
                
                // Przekierowanie powrotne z informacją o usunięciu
                header("Location: index.php?page=product&id=" . $productId . "&status=review_deleted#reviews");
                exit;
            }
        }
        header("Location: index.php");
        exit;
    }
}
