<?php
class PriceChangedException extends Exception {}
class ProductUnavailableException extends Exception {}

class OrderManager
{
    public const STATUS_DICTIONARY = [
        'NEW'              => ['label' => 'Nowe', 'color' => 'bg-info text-dark'],
        'PROCESSING'       => ['label' => 'W realizacji', 'color' => 'bg-warning text-dark'],
        'READY_FOR_PICKUP' => ['label' => 'Gotowe do odbioru', 'color' => 'bg-primary'],
        'SHIPPED'          => ['label' => 'Wysłane', 'color' => 'bg-primary'],
        'COMPLETED'        => ['label' => 'Zrealizowane', 'color' => 'bg-success'],
        'CANCELLED'        => ['label' => 'Anulowane', 'color' => 'bg-danger'],
    ];

    public const PAYMENT_STATUS_DICTIONARY = [
        'UNPAID'   => ['label' => 'Oczekuje na płatność', 'color' => 'bg-danger text-white'],
        'PAID'     => ['label' => 'Opłacone', 'color' => 'bg-success text-white'],
        'REFUNDED' => ['label' => 'Zwrócono', 'color' => 'bg-dark text-white'],
    ];

    public const DELIVERY_METHODS = [
        'pickup'    => 'Odbiór osobisty',
        'courier'   => 'Kurier',
        'paczkomat' => 'Paczkomat InPost'
    ];

    public const PAYMENT_METHODS = [
        'cash_on_delivery' => 'Płatność przy odbiorze',
        'payu'             => 'Płatność online (PayU)',
        'bank_transfer'    => 'Przelew tradycyjny',
        'online'           => 'Płatność online',
        'blik'             => 'BLIK',
        'transfer'         => 'Przelew tradycyjny'
    ];

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createOrder($userId, $expectedTotal, $deliveryMethod, $paymentMethod, $shippingData, $items)
    {
        try {
            // Rozpoczynamy transakcję. Jeśli cokolwiek się wysypie, baza nie zapisze niczego.
            $this->pdo->beginTransaction();

            $actualTotal = 0;

            $securedItems = [];

            $stmt = $this->pdo->prepare("
                SELECT pv.variant_price,pv.stock_quantity, pv.attributes, p.name AS product_name 
                FROM product_variants pv
                JOIN products p ON pv.product_id = p.id
                WHERE pv.id = ? FOR UPDATE
            ");

            foreach ($items as $item) {
                $stmt->execute([$item['variant_id']]);
                $dbVariant = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$dbVariant) {
                    // Ponieważ nie ufamy $item['name'], użyjmy bezpiecznego komunikatu
                    throw new ProductUnavailableException("Jeden z wybranych produktów nie jest już dostępny.");
                }

                $dbPrice = $dbVariant['variant_price'];
                $dbStock = $dbVariant['stock_quantity'];
                $quantity = (int)$item['quantity'];


                // Budujemy pełną, bezpieczną nazwę wariantu (np. "Koszula Wiskozowa (size: M, color: light_blue)")
                $attributes = json_decode($dbVariant['attributes'], true);
                $attrString = '';
                if (!empty($attributes)) {
                    // Zamienia tablicę ['size' => 'M', 'color' => 'red'] na string "(size: M, color: red)"
                    $attrParts = [];
                    foreach ($attributes as $key => $val) {
                        $attrParts[] = "$key: $val";
                    }
                    $attrString = ' (' . implode(', ', $attrParts) . ')';
                }

                $securedName = $dbVariant['product_name'] . $attrString;
                if ($quantity > $dbStock) {
                    throw new ProductUnavailableException("Niestety, produkt '{$securedName}' nie jest dostępny w takiej ilości. Pozostało w magazynie: {$dbStock} szt.");
                }

                $actualTotal += ($dbPrice * $quantity);

                // Dodajemy do zaufanej tablicy
                $securedItems[] = [
                    'variant_id' => $item['variant_id'],
                    'name'       => $securedName,
                    'price'      => $dbPrice,
                    'quantity'   => $quantity
                ];
            }

            // Opcjonalnie: Jeśli koszty wysyłki są wliczone w Total, dodajemy je tutaj do $actualTotal.

            // Porównujemy floaty (różnica mniejsza niż 1 grosz)
            if (abs($actualTotal - $expectedTotal) > 0.01) {
                // Cena się nie zgadza! Wyrzucamy wyjątek.
                throw new PriceChangedException("Ceny produktów uległy zmianie w trakcie składania zamówienia.");
            }

            // ZAPIS GŁÓWNEGO ZAMÓWIENIA
            $stmt = $this->pdo->prepare("
                INSERT INTO orders (user_id, total_price, status, delivery_method, payment_method, shipping_address) 
                VALUES (?, ?, 'NEW', ?, ?, ?)
                ");


            //TODO - validate if totalAmount is actually equal to total order amount of the items in db

            // Tablicę z adresem zamieniamy w elegancki JSON
            $stmt->execute([
                $userId,
                $actualTotal,
                $deliveryMethod,
                $paymentMethod,
                json_encode($shippingData, JSON_UNESCAPED_UNICODE)
            ]);

            // Pobieramy ID świeżo utworzonego zamówienia
            $orderId = $this->pdo->lastInsertId();

            // 2. ZAPIS POZYCJI W KOSZYKU (Snapshoty)
            $stmtItem = $this->pdo->prepare("
                INSERT INTO order_items (order_id, variant_id, variant_name, unit_price, quantity) 
                VALUES (?, ?, ?, ?, ?)
                ");

            $stmtUpdateStock = $this->pdo->prepare("
                UPDATE product_variants 
                SET stock_quantity = stock_quantity - ? 
                WHERE id = ?
            ");

            foreach ($securedItems as $secureItem) {
                $stmtItem->execute([
                    $orderId,
                    $secureItem['variant_id'],
                    $secureItem['name'],
                    $secureItem['price'],
                    $secureItem['quantity']
                ]);

                $stmtUpdateStock->execute([
                    $secureItem['quantity'],
                    $secureItem['variant_id']
                ]);
            }

            $this->pdo->commit();

            return $orderId;
        } catch (Exception $e) {
            // Jeśli wystąpił błąd (np. brak prądu na serwerze MySQL), cofamy wszystkie inserty!
            $this->pdo->rollBack();
            throw $e; // Przekazujemy błąd wyżej do kontrolera
        }
    }

    public function getOrderSummary($orderId)
    {
        // 1. Pobieramy główne zamówienie
        $stmt = $this->pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return null; // Zamówienie nie istnieje
        }

        // Rozkodowujemy JSONa z adresem, żeby był wygodny w użyciu w widoku
        if (!empty($order['shipping_address'])) {
            $order['shipping_address'] = json_decode($order['shipping_address'], true);
        }

        // 2. Pobieramy pozycje zamówienia
        $stmtItems = $this->pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmtItems->execute([$orderId]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    public function getOrdersForUser($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT id, total_price, status, delivery_method, payment_method, payment_status, created_at
            FROM orders
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestOrderForUser($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT id, total_price, status, delivery_method, payment_method, payment_status, created_at
            FROM orders 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        return $order ?: null;
    }
}
