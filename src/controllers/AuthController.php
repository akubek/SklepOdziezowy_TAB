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
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

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
                    $success_message = "Konto zostało pomyślnie utworzone! Możesz się teraz zalogować.";
                } catch (PDOException $e) {
                    $error_message = "Wystąpił błąd: " . $e->getMessage();
                }
            }
        }
        require_once '../views/register.php';
    }
    public function showLogin() {
        $login_error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Szukamy użytkownika po e-mailu
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Weryfikujemy, czy użytkownik istnieje i czy hasło pasuje do hasha z bazy
            if ($user && password_verify($password, $user['password_hash'])) {
                
                // Tworzymy sesję
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['first_name'] = $user['first_name'];

                // 4. Przekierowujemy na stronę główną
                header("Location: index.php?page=home");
                exit;
            } else {
                $login_error = "Nieprawidłowy adres e-mail lub hasło.";
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