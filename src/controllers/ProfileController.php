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

    public function update()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];

            // 1. wyczyszczenie danych
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $phone = trim($_POST['phone_number'] ?? '');
            $birthDate = !empty($_POST['birth_date']) ? $_POST['birth_date'] : null;
            $gender = !empty($_POST['gender']) ? $_POST['gender'] : null;

            // walidacja

            // format e-mail
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['profile_error'] = "Podano niepoprawny format adresu e-mail.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // format telefonu
            if (!empty($phone) && !preg_match('/^\+?[0-9\s\-]{9,15}$/', $phone)) {
                $_SESSION['profile_error'] = "Podano niepoprawny numer telefonu.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // daty urodzenia
            if (!empty($birthDate)) {
                $today = date('Y-m-d');
                if ($birthDate > $today) {
                    $_SESSION['profile_error'] = "Data urodzenia nie może być z przyszłości!";
                    header('Location: index.php?page=profile_settings');
                    exit;
                }
            }

            // płeć
            $allowedGenders = ['MALE', 'FEMALE', 'OTHER'];
            if (!empty($gender) && !in_array($gender, $allowedGenders)) {
                $_SESSION['profile_error'] = "Wybrano niepoprawną płeć.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // imię i nazwisko
            if (empty($firstName) || empty($lastName)) {
                $_SESSION['profile_error'] = "Imię i nazwisko są wymagane.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // sprawdzenie czy emial nie jest zajety
            if ($this->userManager->isEmailTaken($email, $userId)) {
                error_log("email zajety");
                $_SESSION['profile_error'] = "Ten adres e-mail jest już zajęty przez innego użytkownika.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // walidacja pomyslana -> zaktualizowanie profilu
            $success = $this->userManager->updateProfile($userId, $firstName, $lastName, $email, $phone, $birthDate, $gender);

            if ($success) {
                $_SESSION['profile_success'] = "Twoje dane zostały pomyślnie zaktualizowane.";
                $_SESSION['first_name'] = $firstName;
            } else {
                $_SESSION['profile_error'] = "Wystąpił błąd serwera podczas zapisywania danych.";
            }
        }

        header('Location: index.php?page=profile_settings');
        exit;
    }

    public function changePassword()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];

            // dane z formularza
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // walidacja

            // nie moga byc puste
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['profile_error'] = "Wszystkie pola są wymagane.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // czy nowe i potwierdzenie to to samo
            if ($newPassword !== $confirmPassword) {
                $_SESSION['profile_error'] = "Nowe hasło i jego powtórzenie nie są identyczne.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // min 8 znakow
            if (strlen($newPassword) < 8) {
                $_SESSION['profile_error'] = "Nowe hasło musi mieć co najmniej 8 znaków.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // obecny hash
            $currentHashInDb = $this->userManager->getPasswordHash($userId);

            // potwierdzenie hasla obecnego
            if (!password_verify($currentPassword, $currentHashInDb)) {
                $_SESSION['profile_error'] = "Obecne hasło jest nieprawidłowe.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // dodatkowe sprawdzenie czy nowe nie bedzie takie jak stare
            if (password_verify($newPassword, $currentHashInDb)) {
                $_SESSION['profile_error'] = "Nowe hasło nie może być takie samo jak obecne.";
                header('Location: index.php?page=profile_settings');
                exit;
            }

            // pomyslne sprawddzenie -> ustawienie nowego hasla
            // zhashowanie
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

            // zapisanie hashu do bazy
            if ($this->userManager->updatePassword($userId, $newHash)) {
                $_SESSION['profile_success'] = "Twoje hasło zostało bezpiecznie zmienione.";
            } else {
                $_SESSION['profile_error'] = "Wystąpił błąd podczas zapisywania hasła w bazie.";
            }
        }

        header('Location: index.php?page=profile_settings');
        exit;
    }
}
