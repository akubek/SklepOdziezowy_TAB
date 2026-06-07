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
    // Zabezpieczenie dostępu
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'MANAGER') {
        header('Location: /index.php?page=errors/403');
        exit;
    }

    $categories = $this->categoryManager->getCategoryNestedTree();
    $brands = $this->productManager->getAllBrands(); 

    // Zmienne na wyniki raportów (domyślnie puste)
    $salesData = null;
    $demoData = null;
    
    // Sprawdzamy, która zakładka powinna być aktywna po przeładowaniu strony
    $activeTab = 'sales'; 

    // Sprawdzamy, co chcemy wygenerować (na podstawie ukrytego pola action)
    $action = $_GET['action'] ?? null;

    if ($action === 'generate_sales') {
        $activeTab = 'sales';
        // Walidacja dat
        if (!empty($_GET['date_from']) && !empty($_GET['date_to'])) {
            $salesData = $this->reportManager->getSalesTrend($_GET);
        }
    } 
    elseif ($action === 'generate_demo') {
        $activeTab = 'demo';
        $demoData = $this->reportManager->getDemographicsRanking($_GET);
    }

    // Renderujemy widok, przekazując mu wszystko
    renderView('admin/reports/index', [
        'active_tab' => $activeTab,
        'categories' => $categories,
        'brands'     => $brands,
        'salesData'  => $salesData,
        'demoData'   => $demoData
    ]);
}
}
