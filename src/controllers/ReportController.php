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
            header('Location: /index.php?page=403');
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

        // 1. Walidacja danych wejściowych
        $allTime = !empty($_GET['all_time']);
        $activeFrom = $_GET['active_from'] ?? null;
        $activeTo = $_GET['active_to'] ?? null;

        $ageGroups = $_GET['age_groups'] ?? [];
        $genders = $_GET['genders'] ?? [];
        $cities = !empty($_GET['cities']) ? explode(',', $_GET['cities']) : [];
        $groupBy = $_GET['group_by_type'] ?? 'products';

        $groupByCol = match ($groupBy) {
            'brands' => "brand_name",
            'categories' => "COALESCE(parent_category_name || ' ➔ ' || category_name, category_name)",
            default => "product_name"
        };

        // Walidacja dat
        if (!$allTime && (!$activeFrom || !$activeTo || strtotime($activeFrom) > strtotime($activeTo))) {
            $viewData['errors'][] = "Błędny zakres dat aktywności.";
            return $viewData;
        }

        // Walidacja checkboxów
        if (empty($ageGroups)) {
            $viewData['errors'][] = "Wybierz co najmniej jedną grupę wiekową.";
            return $viewData;
        }
        if (empty($genders)) {
            $viewData['errors'][] = "Wybierz co najmniej jedną płeć.";
            return $viewData;
        }

        // 2. Przygotowanie danych do Managera
        $filters = [
            'all_time'   => $allTime,
            'active_from' => $activeFrom,
            'active_to'  => $activeTo,
            'age_groups' => $ageGroups,
            'genders'    => $genders,
            'cities'     => array_map('trim', $cities), // Czyścimy spacje z nazw miast
            'group_by'   => ($groupBy === 'brands') ? 'brand_name' : 'product_name',
            'group_by_col' => $groupByCol,
            'group_by_label' => ($groupBy === 'brands') ? 'Marka' : (($groupBy === 'categories') ? 'Kategoria' : 'Produkt')
        ];

        // 3. Wywołanie Managera
        try {
            $viewData['demoData'] = $this->reportManager->getDemographicsRanking($filters);
        } catch (\Exception $e) {
            $viewData['errors'][] = "Błąd raportu demograficznego: " . $e->getMessage();
        }

        return $viewData;
    }
}
