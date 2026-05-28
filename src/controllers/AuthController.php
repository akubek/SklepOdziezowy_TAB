<?php
class AuthController
{
    private const ALLOWED_REDIRECTS = [
        'checkout_form',
        'cart',
        'profile',
        'home',
        'admin_orders',
        'admin_order_details'
    ];

    private $authManager;
    private $userManager;

    public function __construct($authManager, $userManager)
    {
        $this->authManager = $authManager;
        $this->userManager = $userManager;
    }

    public function showRegister()
    {
        $error_message = '';
        $success_message = '';

        if (isset($_GET['redirect']) && in_array($_GET['redirect'], self::ALLOWED_REDIRECTS)) {
            $_SESSION['intended_redirect'] = $_GET['redirect'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Niepoprawny format adresu e-mail.";
            } elseif (strlen($password) < 8) {
                $error_message = "Hasło musi mieć co najmniej 8 znaków.";
            } elseif (empty($firstName) || empty($lastName)) {
                $error_message = "Imię i nazwisko są wymagane.";
            } else {
                try {
                    $existingUser = $this->userManager->getUserByEmail($email);

                    if ($existingUser && $existingUser['role'] !== 'GUEST') {
                        $error_message = "Konto z tym adresem e-mail już istnieje!";
                    } else {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        if ($existingUser && $existingUser['role'] === 'GUEST') {
                            // SCENARIUSZ 1: Użytkownik kupował jako gość -> Zmieniamy go w pełne konto
                            $this->userManager->upgradeGuestToClient(
                                $existingUser['id'],
                                $firstName,
                                $lastName,
                                $hashedPassword
                            );
                        } else {
                            // SCENARIUSZ 2: Użytkownik jest całkowicie nowy -> Tworzymy konto
                            $this->userManager->createUser(
                                $firstName,
                                $lastName,
                                $email,
                                $hashedPassword
                            );
                        }
                        $_SESSION['flash_success'] = "Konto zostało pomyślnie utworzone! Możesz się teraz zalogować.";
                        header("Location: index.php?page=login");
                        exit;
                    }
                } catch (PDOException $e) {
                    error_log("Registration error for email {$email} : " . $e->getMessage());
                    if (in_array((string) $e->getCode(), ['23000', '23505'], true)) {
                        $error_message = "Konto z tym adresem e-mail już istnieje";
                    } else {
                        $error_message = "Błąd podczas rejestracji";
                    }
                }
            }
        }
        renderView('register', [
            'error_message'     => $error_message,
            'success_message'   => $success_message
        ]); //todo add old_input?
    }

    public function showLogin()
    {
        $login_error = '';

        $success_message = $_SESSION['flash_success'] ?? '';
        unset($_SESSION['flash_success']);

        if (isset($_GET['redirect']) && in_array($_GET['redirect'], self::ALLOWED_REDIRECTS)) {
            $_SESSION['intended_redirect'] = $_GET['redirect'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';

            // additional email format check
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // user is registered in database and passwords match
                if ($this->authManager->login($email, $password)) {
                    // redirect back to intended page or main page
                    $targetPage = $_SESSION['intended_redirect'] ?? 'home';
                    unset($_SESSION['intended_redirect']);
                    header("Location: index.php?page=" . $targetPage);
                    exit;
                } else {
                    $login_error = "Nieprawidłowy adres e-mail lub hasło.";
                }
            } else {
                $login_error = "Nieprawidłowy format e-mail.";
            }
        }
        renderView('login', [
            'login_error'       => $login_error,
            'success_message'   => $success_message
        ]);
    }

    public function logout()
    {
        // Expire the cart cookie to prevent it from persisting on shared computers
        setcookie('cart', '', time() - 3600, '/');

        // Czyścimy wszystkie dane sesji
        session_unset();
        session_destroy();

        // Przekierowujemy na stronę główną
        header("Location: index.php?page=home");
        exit;
    }
}
