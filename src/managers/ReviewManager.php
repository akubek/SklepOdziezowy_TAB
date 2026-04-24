<?php
class ReviewManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 1. Pobiera opinie dla danego produktu wraz z imieniem autora
    public function getReviewsByProductId($productId) {
        $sql = "
            SELECT r.*, u.first_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = :product_id 
            ORDER BY r.created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Wylicza średnią ocen i zlicza wszystkie opinie
    public function getAverageRating($productId) {
        $sql = "
            SELECT ROUND(AVG(rating), 1) as avg_rating, COUNT(id) as review_count 
            FROM reviews 
            WHERE product_id = :product_id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Sprawdza, czy użytkownik kupił ten produkt
    private function checkIfVerifiedPurchase($userId, $productId) {
        $sql = "
            SELECT 1 
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN product_variants pv ON oi.variant_id = pv.id
            WHERE o.user_id = :user_id AND pv.product_id = :product_id
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);

        return $stmt->fetch() ? true : false;
    }

    // Zapisuje opinię z automatyczną weryfikacją ---
    public function addReview($productId, $userId, $rating, $comment) {
        // Automatycznie sprawdzamy weryfikację przed zapisem!
        $isVerified = $this->checkIfVerifiedPurchase($userId, $productId);

        $sql = "
            INSERT INTO reviews (product_id, user_id, rating, comment, is_verified) 
            VALUES (:product_id, :user_id, :rating, :comment, :is_verified)
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'product_id' => $productId,
            'user_id' => $userId,
            'rating' => $rating,
            'comment' => $comment,
            'is_verified' => $isVerified ? 'true' : 'false'
        ]);
    }
    public function deleteReview($reviewId, $userId) {
        // Warunek user_id = :user_id gwarantuje, że usuniemy tylko SWOJĄ opinię
        $sql = "DELETE FROM reviews WHERE id = :review_id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'review_id' => $reviewId,
            'user_id' => $userId
        ]);
    }
    public function hasUserReviewedProduct($userId, $productId) {
        $sql = "SELECT 1 FROM reviews WHERE user_id = :user_id AND product_id = :product_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        return $stmt->fetch() ? true : false;
    }
}
