<?php
class ProductManager {
    private $pdo;

    // connect to database PHP data object
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // get all products with thier categories
    public function getAllProducts() {
        $sql = "
            SELECT p.id, p.name, p.base_price, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // load all products from given category and its subcategories. f.e. "męska -> tshirt+kurtki->(zimowe+inne+...)+koszule..."
    public function getProductsByCategory($categoryId) {
        $sql = "
            WITH RECURSIVE CategoryTree AS (
                -- Starting category
                SELECT id FROM categories WHERE id = :category_id
                UNION
                -- recursively add all categories
                SELECT c.id FROM categories c
                INNER JOIN CategoryTree ct ON c.parent_id = ct.id
            )
            -- get all the products in category tree
            SELECT p.*, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.category_id IN (SELECT id FROM CategoryTree)
            ORDER BY p.id DESC;
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
