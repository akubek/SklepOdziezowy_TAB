<?php

class OrderFulfillmentController {
    private $fulfillmentManager;

    public function __construct($fulfillmentManager) {
        $this->fulfillmentManager = $fulfillmentManager;
        $this->requireStaffAccess(); 
    }

    // STRAŻNIK 
    private function requireStaffAccess() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
            header("Location: index.php?page=login");
            exit;
        }
        
        if (!in_array($_SESSION['role'], ['EMPLOYEE', 'MANAGER'])) {
            header("Location: index.php?page=403");
            exit;
        }
    }

    public function index() {
        // Aktywne zamówienia do realizacji
        $orders = $this->fulfillmentManager->getActiveOrders();
        
        // Historia zamówień (zrealizowane/anulowane)
        $resolvedOrders = $this->fulfillmentManager->getResolvedOrders();
        
        renderView('admin/order_list', [
            'orders' => $orders,
            'resolvedOrders' => $resolvedOrders
        ]);
    }

    public function show() {
        $orderId = $_GET['id'] ?? null;
        if (!$orderId) {
            header("Location: index.php?page=admin_orders");
            exit;
        }

        $order = $this->fulfillmentManager->getOrderById($orderId);
        
        if (!$order) {
            header("Location: index.php?page=404");
            exit;
        }

        $items = $this->fulfillmentManager->getOrderItems($orderId);
        
        renderView('admin/order_details', [
            'order' => $order,
            'items' => $items
        ]);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $newStatus = $_POST['status'] ?? null;
            $employeeId = $_SESSION['user_id'];

            if ($orderId && $newStatus) {
                $this->fulfillmentManager->updateOrderStatus($orderId, $newStatus, $employeeId);
                header("Location: index.php?page=admin_order_details&id=" . $orderId . "&status=updated");
                exit;
            }
        }
        header("Location: index.php?page=admin_orders");
        exit;
    }
}