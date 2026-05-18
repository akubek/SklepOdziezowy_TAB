<?php
// src/controllers/ProfileController.php
class ProfileController
{
    private $userManager;
    private $orderManager;
    private $reviewManager;

    public function __construct($userManager, $orderManager, $reviewManager)
    {
        $this->userManager = $userManager;
        $this->orderManager = $orderManager;
        $this->reviewManager = $reviewManager;
    }

    private function requireLogin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function index()
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $user = $this->userManager->getUserById($userId);
        $lastOrder = $this->orderManager->getLatestOrderForUser($userId);

        renderView('profile/index', [
            'user' => $user,
            'active_tab' => 'dashboard',
            'last_order' => $lastOrder
        ]);
    }

    public function settings()
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $user = $this->userManager->getUserById($userId);


        // Zbieramy komunikaty z sesji (flash messages)
        $success = $_SESSION['profile_success'] ?? '';
        $error = $_SESSION['profile_error'] ?? '';
        unset($_SESSION['profile_success'], $_SESSION['profile_error']);

        renderView('profile/settings', [
            'user' => $user,
            'active_tab' => 'settings',
            'success_message' => $success,
            'error_message' => $error
        ]);
    }

    public function orders()
    {
        $this->requireLogin();
        $orders = $this->orderManager->getOrdersForUser($_SESSION['user_id']);

        $onlineMethods = ['payu', 'blik', 'transfer', 'online', 'bank_transfer'];

        foreach ($orders as &$order) {
            $order['requires_payment'] = (
                $order['payment_status'] === 'UNPAID' &&
                in_array($order['payment_method'], $onlineMethods) &&
                $order['status'] !== 'CANCELLED'
            );
        }
        unset($order);

        renderView('profile/orders', [
            'orders' => $orders,
            'active_tab' => 'orders'
        ]);
    }

    public function orderDetails()
    {
        $this->requireLogin();

        $orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($orderId <= 0) {
            header('Location: index.php?page=profile_orders');
            exit;
        }

        $order = $this->orderManager->getOrderSummary($orderId);

        if (!$order || $order['user_id'] !== $_SESSION['user_id']) {
            header('Location: index.php?page=403');
            exit;
        }

        $onlineMethods = ['payu', 'blik', 'transfer', 'online', 'bank_transfer'];
        $requiresPayment = (
            $order['payment_status'] === 'UNPAID' &&
            in_array($order['payment_method'], $onlineMethods) &&
            $order['status'] !== 'CANCELLED'
        );

        renderView('profile/order_details', [
            'order' => $order,
            'active_tab' => 'orders',
            'requires_payment' => $requiresPayment
        ]);
    }

    public function reviews()
    {
        $this->requireLogin();
        $reviews = $this->reviewManager->getReviewsForUser($_SESSION['user_id']);

        renderView('profile/reviews', [
            'active_tab' => 'reviews',
            'reviews' => $reviews
        ]);
    }

    public function deleteReview()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['review_id'])) {
            $reviewId = (int)$_POST['review_id'];

            $deleted = $this->reviewManager->deleteReview($reviewId, $_SESSION['user_id']);

            if ($deleted) {
                $_SESSION['profile_success'] = "Opinia została pomyślnie usunięta.";
            } else {
                $_SESSION['profile_error'] = "Nie udało się usunąć opinii.";
            }
        }
        header('Location: index.php?page=profile_reviews');
        exit;
    }

    public function addresses()
    {
        $this->requireLogin();
        $user = $this->userManager->getUserById($_SESSION['user_id']);
        $addresses = json_decode($user['addresses'] ?? '[]', true);

        renderView('profile/addresses', [
            'active_tab' => 'addresses',
            'addresses' => $addresses
        ]);
    }
}
