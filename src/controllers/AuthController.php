<?php
class AuthController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function showRegister() {
        $error_message = '';
        $success_message = '';

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
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                
                if ($stmt->fetch()) {
                    $error_message = "Konto z tym adresem e-mail już istnieje!";
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $insertStmt = $this->pdo->prepare("
                        INSERT INTO users (first_name, last_name, email, password_hash) 
                        VALUES (:first_name, :last_name, :email, :password_hash)
                    ");
                    try {
                        $insertStmt->execute([
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => $email,
                            'password_hash' => $hashedPassword
                        ]);

                        $_SESSION['flash_success'] = "Konto zostało pomyślnie utworzone! Możesz się teraz zalogować.";
                        header("Location: index.php?page=login");
                        exit;
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
        }
        require_once '../views/register.php';
    }
    public function showLogin() {
        $login_error = '';
        
        $success_message = $_SESSION['flash_success'] ?? '';
        unset($_SESSION['flash_success']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';

            // additional email format check
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // find user by email
                $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // user is registered in database and passwords match
                if ($user && password_verify($password, $user['password_hash'])) {
                    
                    // create session for user
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['first_name'] = $user['first_name'];

                    // redirect to main page
                    header("Location: index.php?page=home");
                    exit;
                } else {
                    $login_error = "Nieprawidłowy adres e-mail lub hasło.";
                }
            } else {
                $login_error = "Nieprawidłowy format e-mail.";
            }
        }
        require_once '../views/login.php';
    }
    public function logout() {
        // Czyścimy wszystkie dane sesji
        session_unset();
        session_destroy();
        
        // Przekierowujemy na stronę główną
        header("Location: index.php?page=home");
        exit;
    }
}
