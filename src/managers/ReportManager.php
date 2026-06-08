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
}
