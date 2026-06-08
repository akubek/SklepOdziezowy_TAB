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
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'MANAGER') {
            header('Location: /index.php?page=errors/403');
            exit;
        }
        // Podstawowe dane dla formularzy
        $viewData = [
            'active_tab' => 'sales',
            'categories' => $this->categoryManager->getCategoryNestedTree(),
            'brands'     => $this->productManager->getAllBrands(),
            'salesData'  => null,
            'demoData'   => null,
            'errors'     => [] //tablica na błędy walidacji
        ];

        $action = $_GET['action'] ?? null;

        if ($action === 'generate_sales') {
            $viewData = $this->handleSalesRequest($viewData);
        } elseif ($action === 'generate_demo') {
            $viewData = $this->handleDemoRequest($viewData);
        }

        renderView('admin/reports/index', $viewData);
    }

    private function handleSalesRequest(array $viewData): array
    {
        $viewData['active_tab'] = 'sales';

        // 1. Walidacja w kontrolerze
        $allTime = !empty($_GET['all_time']);
        $dateFrom = $_GET['date_from'] ?? null;
        $dateTo = $_GET['date_to'] ?? null;
        $categories = $_GET['categories'] ?? [];
        $allBrands = !empty($_GET['all_brands']);
        $brands = $_GET['brands'] ?? [];

        if (!$allTime) {
            if (!$dateFrom || !$dateTo || strtotime($dateFrom) > strtotime($dateTo)) {
                $viewData['errors'][] = "Błędny zakres dat.";
                return $viewData;
            }
        }

        if (empty($categories)) {
            $viewData['errors'][] = "Wybierz co najmniej jedną kategorię.";
            return $viewData;
        }

        if (!$allBrands && empty($brands)) {
            $viewData['errors'][] = "Wybierz co najmniej jedną markę.";
            return $viewData;
        }

        // 2. Przygotowanie czystych danych dla Managera
        $filters = [
            'all_time'   => $allTime,
            'date_from'  => $dateFrom,
            'date_to'    => $dateTo,
            'categories' => $categories,
            'all_brands' => $allBrands,
            'brands'     => $brands
        ];

        // 3. Odpytanie Managera
        try {
            $viewData['salesData'] = $this->reportManager->getSalesTrend($filters);
        } catch (\Exception $e) {
            $viewData['errors'][] = "Błąd generowania raportu: " . $e->getMessage();
        }

        return $viewData;
    }

    private function handleDemoRequest(array $viewData): array
    {
        $viewData['active_tab'] = 'demo';
        // Analogicznie: walidacja $_GET, przygotowanie filtrów i wywołanie $reportManager->getDemographicsRanking()
        return $viewData;
    }
}
