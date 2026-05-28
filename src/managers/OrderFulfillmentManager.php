<?php

class OrderFulfillmentManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // 1. Pobiera aktywne zamówienia do realizacji
    public function getActiveOrders() {
        $sql = "
            SELECT o.id, o.status, o.total_price, o.created_at, 
                   u.first_name, u.last_name, u.email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.status IN ('NEW', 'PAID', 'PROCESSING')
            ORDER BY o.created_at ASC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Pobiera zrealizowane/anulowane zamówienia (Historia)
    public function getResolvedOrders() {
        $sql = "
            SELECT o.id, o.status, o.total_price, o.created_at, 
                   u.first_name, u.last_name, u.email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.status IN ('SHIPPED', 'COMPLETED', 'CANCELLED')
            ORDER BY o.created_at DESC
            LIMIT 50
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Pobiera szczegóły konkretnego zamówienia
    public function getOrderById($orderId) {
        $sql = "
            SELECT o.*, 
                   u.first_name as client_first_name, u.last_name as client_last_name, u.email as client_email,
                   e.first_name as emp_first_name, e.last_name as emp_last_name
            FROM orders o
            JOIN users u ON o.user_id = u.id
            LEFT JOIN users e ON o.employee_id = e.id
            WHERE o.id = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Pobiera produkty dla danego zamówienia
    public function getOrderItems($orderId) {
        $sql = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Aktualizuje status i zapisuje ID pracownika
    public function updateOrderStatus($orderId, $status, $employeeId) {
        $sql = "UPDATE orders SET status = :status, employee_id = :employee_id";
        
        if ($status === 'SHIPPED') {
            $sql .= ", shipped_at = CURRENT_TIMESTAMP";
        } elseif ($status === 'COMPLETED') {
            $sql .= ", completed_at = CURRENT_TIMESTAMP";
        }
        
        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'status' => $status,
            'employee_id' => $employeeId,
            'id' => $orderId
        ]);
    }
}