<?php
class ReportController
{
    private $reportManager;
    private $categoryManager;
    private $productManager;

    public function __construct($reportManager, $categoryManager, $productManager)
    {
        // Zakładam, że wstrzykujesz zależności przez kontener, tak jak w innych kontrolerach
        $this->reportManager = $reportManager;
        $this->categoryManager = $categoryManager;
        $this->productManager = $productManager;
    }

    // 1. Zwraca główny widok HTML panelu
    public function index()
    {
        // 1. Zabezpieczenie dostępu
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'MANAGER') {
            header('Location: /index.php?page=errors/403');
            exit;
        }

        // 2. Pobranie managerów z kontenera (zakładam, że masz do niego dostęp w kontrolerze)

        // 3. Pobranie list do formularzy (tzw. dane słownikowe)
        // UWAGA: Użyj odpowiednich metod z Twoich managerów, np.:
        $categories = $this->categoryManager->getCategoryNestedTree();
        // Jeśli nie masz metody getAllBrands(), możesz ją dopisać w ProductManager 
        // (SELECT DISTINCT brand_name FROM products WHERE brand_name IS NOT NULL)
        $brands = $this->productManager->getAllBrands();

        // 4. Renderowanie widoku i wstrzyknięcie zmiennych
        renderView('admin/reports/index', [
            'active_tab' => 'reports', // Żeby np. podświetlić menu
            'categories' => $categories,
            'brands'     => $brands
        ]);
    }
}
