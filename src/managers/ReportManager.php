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

    public function getDemographicsRanking($filters)
    {
        // 1. Zabezpieczenie wyboru kolumny do grupowania (unikamy SQL Injection)
        $groupByCol = ($filters['group_by_type'] === 'brands') ? 'brand_name' : 'product_name';

        $query = "SELECT $groupByCol AS name, 
                     SUM(total_products_bought) AS quantity, 
                     SUM(total_amount_spent) AS revenue
              FROM v_demographics_report 
              WHERE 1=1 ";
        $params = [];

        // Opcjonalny filtr wieku
        if (!empty($filters['age_groups'])) {
            // Generuje: AND age_group IN (?, ?, ?)
            $inQuery = implode(',', array_fill(0, count($filters['age_groups']), '?'));
            $query .= " AND age_group IN ($inQuery)";
            $params = array_merge($params, $filters['age_groups']);
        }

        // Opcjonalny filtr daty aktywności
        if (!empty($filters['active_from']) && !empty($filters['active_to'])) {
            $query .= " AND order_date BETWEEN ? AND ?";
            $params[] = $filters['active_from'];
            $params[] = $filters['active_to'];
        }

        // (Podobnie dla płci i miast - miasta możesz rozbić po przecinku funkcją explode)

        // Agregacja i sortowanie od najpopularniejszego
        $query .= " GROUP BY $groupByCol 
                ORDER BY quantity DESC 
                LIMIT 20"; // Pokaż tylko Top 20, żeby wykres był czytelny

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Miejsce na kolejną metodę, np. dla sprzedaży dziennej
    public function getSalesTrend($filters)
    {
        $query = "SELECT order_date, 
                     SUM(total_products_bought) AS daily_items, 
                     SUM(total_amount_spent) AS daily_revenue
              FROM v_demographics_report 
              WHERE order_date BETWEEN ? AND ? ";

        $params = [$filters['date_from'], $filters['date_to']];

        // Opcjonalne filtry np. ignoruj wybrane marki, dodajemy tak samo jak wyżej

        $query .= " GROUP BY order_date 
                ORDER BY order_date ASC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $dailyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Wyliczenie "Całości", o którą pytałeś, prosto z wyników dziennych
        $totalItems = 0;
        $totalRevenue = 0;
        foreach ($dailyData as $row) {
            $totalItems += $row['daily_items'];
            $totalRevenue += $row['daily_revenue'];
        }

        return [
            'totals' => [
                'items' => $totalItems,
                'revenue' => number_format((float)$totalRevenue, 2, '.', '')
            ],
            'trend' => $dailyData // to trafi na wykres
        ];
    }
}
