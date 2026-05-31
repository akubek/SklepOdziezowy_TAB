<?php

class InventoryController {
    private $inventoryManager;

    public function __construct($inventoryManager) {
        $this->inventoryManager = $inventoryManager;
        $this->requireManagerAccess();
    }

    // --- STRAŻNIK DLA MANAGERA ---
    private function requireManagerAccess() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header("Location: index.php?page=login");
            exit;
        }
        
        if ($_SESSION['role'] !== 'MANAGER') {
            // Zwykli pracownicy (EMPLOYEE) i Klienci (CLIENT) odbiją się tutaj
            header("Location: index.php?page=403");
            exit;
        }
    }

   public function index() {
        // Paginacja
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $limit = 30;
        $offset = ($page - 1) * $limit;

        $totalProducts = $this->inventoryManager->getProductsCount();
        $totalPages = ceil($totalProducts / $limit);
        if ($totalPages == 0) $totalPages = 1; // Zabezpieczenie przed 0 stron

        $categories = $this->inventoryManager->getAllCategories();
        $products = $this->inventoryManager->getAllProducts($limit, $offset);
        
        renderView('admin/inventory_list', [
            'categories' => $categories,
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    // --- OBSŁUGA KATEGORII ---

    public function showCategoryForm() {
        $id = $_GET['id'] ?? null;
        $category = $id ? $this->inventoryManager->getCategoryById($id) : null;
        
        // Pobieramy wszystkie kategorie do listy rozwijanej "Kategoria nadrzędna"
        $allCategories = $this->inventoryManager->getAllCategories();

        renderView('admin/category_form', [
            'category' => $category,
            'allCategories' => $allCategories
        ]);
    }

    public function saveCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $parentId = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
            $imagePath = trim($_POST['image_path'] ?? '');

            if ($name) {
                $this->inventoryManager->saveCategory($id, $name, $parentId, $imagePath);
            }
        }
        header("Location: index.php?page=admin_inventory");
        exit;
    }

    public function deleteCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                try {
                    $success = $this->inventoryManager->deleteCategory($id);
                    if (!$success) {
                        header("Location: index.php?page=409");
                        exit;
                    }
                } catch (PDOException $e) {
                    header("Location: index.php?page=409");
                    exit;
                }
            }
        }
        header("Location: index.php?page=admin_inventory");
        exit;
    }

    // --- OBSŁUGA PRODUKTÓW ---

    public function showProductForm() {
        $id = $_GET['id'] ?? null;
        $product = $id ? $this->inventoryManager->getProductById($id) : null;
        
        // Musimy pobrać kategorie, żeby móc przypisać produkt do jednej z nich
        $allCategories = $this->inventoryManager->getAllCategories();

        renderView('admin/product_form', [
            'product' => $product,
            'allCategories' => $allCategories
        ]);
    }

    public function saveProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $brandName = trim($_POST['brand_name'] ?? '');
            $mainImage = trim($_POST['main_image'] ?? '');
            $categoryId = $_POST['category_id'] ?? null;
            // Zmieniamy przecinki na kropki (żeby baza przyjęła ułamki)
            $basePrice = (float) str_replace(',', '.', $_POST['base_price'] ?? 0);
            
            // Utrzymujemy pusty JSONB dla atrybutów na tym etapie
            $attributes = '{}'; 

            if ($name && $categoryId && $basePrice >= 0) {
                $this->inventoryManager->saveProduct($id, $name, $description, $brandName, $mainImage, $categoryId, $basePrice, $attributes);
            }
        }
        header("Location: index.php?page=admin_inventory");
        exit;
    }

   public function deleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                try {
                    $success = $this->inventoryManager->deleteProduct($id);
                    if (!$success) {
                        header("Location: index.php?page=409");
                        exit;
                    }
                } catch (PDOException $e) {
                    header("Location: index.php?page=409");
                    exit;
                }
            }
        }
        header("Location: index.php?page=admin_inventory");
        exit;
    }
    // --- OBSŁUGA WARIANTÓW ---

    public function showVariantsList() {
        $productId = $_GET['product_id'] ?? null;
        if (!$productId) {
            header("Location: index.php?page=admin_inventory");
            exit;
        }

        $product = $this->inventoryManager->getProductById($productId);
        $variants = $this->inventoryManager->getVariantsByProductId($productId);

        renderView('admin/variant_list', [
            'product' => $product,
            'variants' => $variants
        ]);
    }

    public function showVariantForm() {
        $id = $_GET['id'] ?? null;
        $productId = $_GET['product_id'] ?? null;
        
        $variant = $id ? $this->inventoryManager->getVariantById($id) : null;
        if ($variant) {
            $productId = $variant['product_id'];
        }

        $product = $this->inventoryManager->getProductById($productId);

        renderView('admin/variant_form', [
            'variant' => $variant,
            'product' => $product
        ]);
    }

    public function saveVariant() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $productId = $_POST['product_id'] ?? null;
            $sku = trim($_POST['sku'] ?? '');
            $variantPrice = (float) str_replace(',', '.', $_POST['variant_price'] ?? 0);
            $stockQuantity = (int) $_POST['stock_quantity'] ?? 0;
            
            // Walidacja JSONa
            $attributes = trim($_POST['attributes'] ?? '{}');
            if (empty($attributes) || json_decode($attributes) === null) { $attributes = '{}'; }
            
            $images = trim($_POST['images'] ?? '[]');
            if (empty($images) || json_decode($images) === null) { $images = '[]'; }

            if ($productId && $sku && $variantPrice >= 0 && $stockQuantity >= 0) {
                try {
                    $this->inventoryManager->saveVariant($id, $productId, $sku, $variantPrice, $stockQuantity, $attributes, $images);
                } catch (PDOException $e) {
                    // Wpadnie tutaj np. jak podamy SKU, które już istnieje w bazie (UNIQUE)
                }
            }
        }
        header("Location: index.php?page=admin_variants&product_id=" . ($_POST['product_id'] ?? ''));
        exit;
    }

    public function deleteVariant() {
        $productId = $_POST['product_id'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                try {
                    $this->inventoryManager->deleteVariant($id);
                } catch (PDOException $e) {}
            }
        }
        header("Location: index.php?page=admin_variants&product_id=" . $productId);
        exit;
    }
}
