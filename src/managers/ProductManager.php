<?php
class ProductManager
{
    private $pdo;

    // connect to database PHP data object
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // get all products with thier categories
    public function getAllProducts()
    {
        $sql = "
            SELECT p.id, p.name, p.base_price, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // load all products from given category and its subcategories. f.e. "męska -> tshirt+kurtki->(zimowe+inne+...)+koszule..."
    public function getProductsByCategory($categoryId, $limit = null, $orderBy = 'newest')
    {

        // Bezpieczne mapowanie sortowania (chroni przed SQL Injection)
        $orderings = [
            'newest'     => 'p.id DESC',
            'price_asc'  => 'p.base_price ASC',
            'price_desc' => 'p.base_price DESC',
            'name_asc'   => 'p.name ASC'
        ];

        // Jeśli ktoś przekaże błędny parametr, domyślnie bierzemy 'newest'
        $sqlOrder = $orderings[$orderBy] ?? $orderings['newest'];

        $sql = "
            WITH RECURSIVE CategoryTree AS (
                SELECT id FROM categories WHERE id = :category_id
                UNION
                SELECT c.id FROM categories c
                INNER JOIN CategoryTree ct ON c.parent_id = ct.id
            )
            SELECT p.*, c.name as category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.category_id IN (SELECT id FROM CategoryTree)
            ORDER BY $sqlOrder
        ";

        // Jeśli podano limit, doklejamy go do zapytania
        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);

        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductWithVariants($productId)
    {
        // get product by productId 
        $stmt = $this->pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
        $stmt->execute(['id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) return null;

        // get all variants for given product
        $stmt = $this->pdo->prepare("SELECT * FROM product_variants WHERE product_id = :id");
        $stmt->execute(['id' => $productId]);
        $product['variants'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $product;
    }

    public function getVariantsWithProductInfo($variantIds)
    {
        if (empty($variantIds)) return [];

        $placeholders = implode(',', array_fill(0, count($variantIds), '?'));
        $sql = "
            SELECT 
                pv.id as variant_id, pv.sku, pv.attributes, pv.images, 
                pv.price_modifier, pv.stock_quantity,
                p.name as product_name, p.base_price
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.id
            WHERE pv.id IN ($placeholders)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($variantIds);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestProducts($limit = 4)
    {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT " . (int)$limit);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
