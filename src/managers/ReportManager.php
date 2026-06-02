<?php
class ReportManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Metoda pobierająca dane do wykresu demograficznego
    public function getDemographicsData($dateFrom, $dateTo)
    {
        $stmt = $this->pdo->prepare("
            SELECT age_group, gender, SUM(total_amount_spent) AS revenue
            FROM v_demographics_report
            WHERE order_date >= :date_from AND order_date <= :date_to
            GROUP BY age_group, gender
            ORDER BY age_group ASC
        ");

        $stmt->execute([
            ':date_from' => $dateFrom,
            ':date_to' => $dateTo
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Miejsce na kolejną metodę, np. dla sprzedaży dziennej
    public function getSalesTrendData($dateFrom, $dateTo)
    {
        // ... zapytanie grupujące po order_date ...
        return [];
    }
}
