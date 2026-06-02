<?php
class ReportController
{
    private $reportManager;

    public function __construct($reportManager)
    {
        // Zakładam, że wstrzykujesz zależności przez kontener, tak jak w innych kontrolerach
        $this->reportManager = $reportManager;
    }

    // 1. Zwraca główny widok HTML panelu
    public function index()
    {
        // Zabezpieczenie dostępu tylko dla managera
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'MANAGER') {
            header('Location: /index.php?page=errors/403');
            exit;
        }

        renderView('admin/reports/index', [
            'active_tab' => 'none',
        ]);
    }

    // 2. Endpoint API dla AJAX/Fetch zwracający JSON (dla Chart.js)
    public function getDemographicsApi()
    {
        // Zabezpieczenie
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'MANAGER') {
            http_response_code(403);
            echo json_encode(['error' => 'Brak dostępu']);
            exit;
        }

        // Pobranie filtrów (zabezpiecz to odpowiednio)
        $dateFrom = $_GET['date_from'] ?? date('Y-m-d', strtotime('-30 days'));
        $dateTo = $_GET['date_to'] ?? date('Y-m-d');

        $data = $this->reportManager->getDemographicsData($dateFrom, $dateTo);

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
