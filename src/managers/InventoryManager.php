<?php

class InventoryManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Pobiera wszystkie kategorie wraz z nazwą ich rodzica
    public function getAllCategories() {
        $sql = "
            SELECT c1.*, c2.name as parent_name 
            FROM categories c1 
            LEFT JOIN categories c2 ON c1.parent_id = c2.id 
            ORDER BY c1.id DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pobiera wszystkie produkty wraz z nazwą kategorii i zsumowanym stanem magazynowym
    public function getProductsCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM products");
        return $stmt->fetchColumn();
    }

    // Dodajemy LIMIT i OFFSET
    public function getAllProducts($limit = 50, $offset = 0) {
        // Musimy zrzutować na (int)
        $limit = (int)$limit;
        $offset = (int)$offset;

        $sql = "
            SELECT p.*, c.name as category_name,
                   COALESCE((SELECT SUM(stock_quantity) FROM product_variants WHERE product_id = p.id), 0) as total_stock
            FROM products p
            JOIN categories c ON p.category_id = c.id
            ORDER BY p.id DESC
            LIMIT $limit OFFSET $offset
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //CRUD KATEGORII 

    public function getCategoryById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveCategory($id, $name, $parentId, $imagePath) {
        if ($id) {
            // Edycja istniejącej
            $stmt = $this->pdo->prepare("UPDATE categories SET name = :name, parent_id = :parent_id, image_path = :image_path WHERE id = :id");
            return $stmt->execute(['name' => $name, 'parent_id' => $parentId, 'image_path' => $imagePath, 'id' => $id]);
        } else {
            // Dodanie nowej
            $stmt = $this->pdo->prepare("INSERT INTO categories (name, parent_id, image_path) VALUES (:name, :parent_id, :image_path)");
            return $stmt->execute(['name' => $name, 'parent_id' => $parentId, 'image_path' => $imagePath]);
        }
    }

    public function deleteCategory($id) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // --- CRUD PRODUKTÓW ---

    public function getProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveProduct($id, $name, $description, $brandName, $mainImage, $categoryId, $basePrice, $attributes = '{}') {
        if ($id) {
            $stmt = $this->pdo->prepare("
                UPDATE products 
                SET name = :name, description = :description, brand_name = :brand_name, 
                    main_image = :main_image, category_id = :category_id, 
                    base_price = :base_price, attributes = :attributes 
                WHERE id = :id
            ");
            return $stmt->execute([
                'name' => $name, 'description' => $description, 'brand_name' => $brandName,
                'main_image' => $mainImage, 'category_id' => $categoryId,
                'base_price' => $basePrice, 'attributes' => $attributes, 'id' => $id
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO products (name, description, brand_name, main_image, category_id, base_price, attributes) 
                VALUES (:name, :description, :brand_name, :main_image, :category_id, :base_price, :attributes)
            ");
            return $stmt->execute([
                'name' => $name, 'description' => $description, 'brand_name' => $brandName,
                'main_image' => $mainImage, 'category_id' => $categoryId,
                'base_price' => $basePrice, 'attributes' => $attributes
            ]);
        }
    }

    public function deleteProduct($id) {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // --- CRUD WARIANTÓW ---

    public function getVariantsByProductId($productId) {
        $stmt = $this->pdo->prepare("SELECT * FROM product_variants WHERE product_id = :product_id ORDER BY id ASC");
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVariantById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM product_variants WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveVariant($id, $productId, $sku, $variantPrice, $stockQuantity, $attributes, $images) {
        if ($id) {
            $stmt = $this->pdo->prepare("
                UPDATE product_variants 
                SET sku = :sku, variant_price = :variant_price, stock_quantity = :stock_quantity, 
                    attributes = :attributes, images = :images
                WHERE id = :id
            ");
            return $stmt->execute([
                'sku' => $sku, 'variant_price' => $variantPrice, 'stock_quantity' => $stockQuantity,
                'attributes' => $attributes, 'images' => $images, 'id' => $id
            ]);
        } else {
            $stmt = $this->pdo->prepare("
                INSERT INTO product_variants (product_id, sku, variant_price, stock_quantity, attributes, images) 
                VALUES (:product_id, :sku, :variant_price, :stock_quantity, :attributes, :images)
            ");
            return $stmt->execute([
                'product_id' => $productId, 'sku' => $sku, 'variant_price' => $variantPrice, 
                'stock_quantity' => $stockQuantity, 'attributes' => $attributes, 'images' => $images
            ]);
        }
    }

    public function deleteVariant($id) {
        $stmt = $this->pdo->prepare("DELETE FROM product_variants WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}