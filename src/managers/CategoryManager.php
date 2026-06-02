<?php
class CategoryManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCategoryNestedTree()
    {
        $stmt = $this->pdo->query("SELECT id, name, parent_id FROM categories ORDER BY name ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->buildNestedTree($categories);
    }

    private function buildNestedTree(array &$elements, $parentId = null)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                // Rekurencyjnie szukamy dzieci dla obecnego elementu
                $children = $this->buildNestedTree($elements, $element['id']);

                // Zawsze dodajemy klucz children (pusta tablica, jeśli brak dzieci)
                $element['children'] = $children ?: [];

                $branch[] = $element;
            }
        }
        return $branch;
    }

    // Pobiera główne kategorie (te, które nie mają rodzica, np. "Odzież")
    public function getRootCategories()
    {
        $stmt = $this->pdo->query("SELECT id, name, image_path FROM categories WHERE parent_id IS NULL");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pobiera podkategorie dla konkretnego rodzica
    public function getSubCategories($parentId)
    {
        $stmt = $this->pdo->prepare("SELECT id, name, image_path FROM categories WHERE parent_id = :parent_id");
        $stmt->bindValue(':parent_id', $parentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Pobiera dane konkretnej kategorii (żeby wyświetlić jej nazwę w nagłówku)
    public function getCategoryById($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, name, image_path FROM categories WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Pobiera pełną ścieżkę od root do bieżącej kategorii (potrzebne do breadcrumbs)
    public function getCategoryPath($categoryId)
    {
        $path = [];
        $currentId = $categoryId;

        while ($currentId !== null) {
            $stmt = $this->pdo->prepare("SELECT id, name, parent_id FROM categories WHERE id = :id");
            $stmt->bindValue(':id', $currentId, PDO::PARAM_INT);
            $stmt->execute();
            $cat = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cat) break;

            // Dodajemy na początek tablicy, żeby zachować kolejność: Główne -> Sub -> Liść
            array_unshift($path, $cat);
            $currentId = $cat['parent_id'];
        }
        return $path;
    }
}
