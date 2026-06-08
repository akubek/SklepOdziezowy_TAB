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


    public function getSalesTrend(array $filters)
    {
        $allTime = $filters['all_time'] ?? false;
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        $categories = $filters['categories'] ?? [];
        $allBrands = $filters['all_brands'] ?? false;
        $brands = $filters['brands'] ?? [];

        $params = [];

        // 1. KATEGORIE (Bindowane jako pierwsze, bo CTE jest na początku SQL)
        $catPlaceholders = implode(',', array_fill(0, count($categories), '?'));
        foreach ($categories as $cat) {
            $params[] = (int) $cat;
        }

        // 2. DATY (Bindowane jako drugie)
        $dateCondition = "";
        if (!$allTime) {
            $dateCondition = "AND sale_date >= ? AND sale_date <= ?";
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }

        // 3. MARKI (Bindowane opcjonalnie na końcu)
        $brandCondition = "";
        if (!$allBrands && !empty($brands)) {
            $brandPlaceholders = implode(',', array_fill(0, count($brands), '?'));
            // Zakładam, że widok ma kolumnę brand_name. Jeśli ma brand_id, podmień.
            $brandCondition = "AND brand_name IN ($brandPlaceholders)";
            foreach ($brands as $brand) {
                $params[] = $brand;
            }
        }

        // GŁÓWNE ZAPYTANIE
        $query = "
            WITH RECURSIVE CategoryTree AS (
                SELECT id FROM categories WHERE id IN ($catPlaceholders)
                UNION
                SELECT c.id FROM categories c
                INNER JOIN CategoryTree ct ON c.parent_id = ct.id
            )
            SELECT 
                sale_date AS order_date, 
                SUM(total_quantity) AS daily_items, 
                SUM(total_revenue) AS daily_revenue
            FROM v_sales_report 
            WHERE category_id IN (SELECT id FROM CategoryTree)
            $dateCondition
            $brandCondition
            GROUP BY sale_date 
            ORDER BY sale_date ASC
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $dailyData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Wyliczenie "Całości"
        $totalItems = 0;
        $totalRevenue = 0;
        $trend = [];
        $lookup = [];
        foreach ($dailyData as $row) {
            $lookup[$row['order_date']] = $row;
            $totalItems += $row['daily_items'];
            $totalRevenue += $row['daily_revenue'];
        }

        // 2. Ustalenie daty początkowej i końcowej do wykresu
        $start = $dateFrom;
        $end = $dateTo;

        if ($allTime) {
            // Jeśli "Cały okres", bierzemy pierwszą i ostatnią datę z bazy (jeśli są dane)
            if (!empty($dailyData)) {
                $start = $dailyData[0]['order_date'];
                $end = end($dailyData)['order_date'];
            } else {
                // Jeśli baza jest całkiem pusta, dajemy dzisiejszy dzień by wykres nie wybuchł
                $start = date('Y-m-d');
                $end = date('Y-m-d');
            }
        }

        if ($start && $end) {
            $currentTimestamp = strtotime($start);
            $endTimestamp = strtotime($end);

            while ($currentTimestamp <= $endTimestamp) {
                $dateString = date('Y-m-d', $currentTimestamp);

                if (isset($lookup[$dateString])) {
                    $trend[] = $lookup[$dateString]; // Mamy sprzedaż w ten dzień
                } else {
                    $trend[] = [                     // Brak sprzedaży -> wstawiamy 0
                        'order_date' => $dateString,
                        'daily_items' => 0,
                        'daily_revenue' => 0.00
                    ];
                }
                $currentTimestamp = strtotime('+1 day', $currentTimestamp);
            }
        }

        return [
            'totals' => [
                'items' => $totalItems,
                'revenue' => number_format((float)$totalRevenue, 2, '.', '')
            ],
            'trend' => $trend
        ];
    }

    public function getDemographicsRanking(array $filters): array
    {
        $params = [];
        $where = [];

        // Daty
        if (!$filters['all_time']) {
            $where[] = "order_date BETWEEN ? AND ?";
            $params[] = $filters['active_from'];
            $params[] = $filters['active_to'];
        }

        // Wiek (IN)
        $agePlaceholders = implode(',', array_fill(0, count($filters['age_groups']), '?'));
        $where[] = "age_group IN ($agePlaceholders)";
        foreach ($filters['age_groups'] as $age) $params[] = $age;

        // Płeć (IN)
        $genderPlaceholders = implode(',', array_fill(0, count($filters['genders']), '?'));
        $where[] = "gender IN ($genderPlaceholders)";
        foreach ($filters['genders'] as $gen) $params[] = $gen;

        // Miasta (Opcjonalnie)
        if (!empty($filters['cities'])) {
            $cityPlaceholders = implode(',', array_fill(0, count($filters['cities']), '?'));
            $where[] = "city IN ($cityPlaceholders)";
            foreach ($filters['cities'] as $city) $params[] = $city;
        }

        $whereSql = "WHERE " . implode(' AND ', $where);
        $groupByCol = $filters['group_by_col']; // bezpieczne, bo wybieramy z listy w kontrolerze

        $query = "
            SELECT 
                $groupByCol AS item_name,
                SUM(total_products_bought) AS total_bought
            FROM v_demographics_report 
            $whereSql
            GROUP BY 1
            ORDER BY 2 DESC
            LIMIT 10
            ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
