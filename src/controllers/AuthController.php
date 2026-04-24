<?php
class AuthController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function showRegister()
    {
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
        renderView('login', [
            'login_error'       => $login_error,
            'success_message'   => $success_message
        ]);
    }

    public function logout()
    {
        // Czyścimy wszystkie dane sesji
        session_unset();
        session_destroy();

        // Przekierowujemy na stronę główną
        header("Location: index.php?page=home");
        exit;
    }

    public function showProfile()
    {
        // Sprawdzenie autoryzacji
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=403');
            exit;
        }

        // Pobieranie danych użytkownika
        $stmt = $this->pdo->prepare("SELECT first_name, last_name, email, created_at FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $success_message = '';
        $error_message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName = trim($_POST['last_name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Podaj poprawny adres e-mail.";
            } else {
                $updateStmt = $this->pdo->prepare("
                    UPDATE users 
                    SET first_name = :first_name, last_name = :last_name, email = :email 
                    WHERE id = :id
                ");

                if ($updateStmt->execute([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'id' => $_SESSION['user_id']
                ])) {
                    $_SESSION['first_name'] = $firstName;
                    $success_message = "Dane zostały zaktualizowane!";
                    $user['first_name'] = $firstName;
                    $user['last_name'] = $lastName;
                    $user['email'] = $email;
                } else {
                    $error_message = "Wystąpił błąd podczas aktualizacji.";
                }
            }
        }
        renderView('profile', [
            'user'              => $user,
            'success_message'   => $success_message,
            'error_message'     => $error_message
        ]);
    }

    public function changePassword()
    {
        // Sprawdzenie autoryzacji
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $error_message = '';
        $success_message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Pobieranie obecnego hasło
            $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = :id");
            $stmt->execute(['id' => $_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Walidacja
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error_message = "Wszystkie pola są wymagane.";
            } elseif (!password_verify($currentPassword, $user['password_hash'])) {
                $error_message = "Obecne hasło jest nieprawidłowe.";
            } elseif ($newPassword !== $confirmPassword) {
                $error_message = "Nowe hasła nie są identyczne.";
            } elseif (strlen($newPassword) < 6) {
                $error_message = "Nowe hasło musi mieć co najmniej 6 znaków.";
            } else {
                // Aktualizacja hasła
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $this->pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");

                if ($updateStmt->execute(['hash' => $newHash, 'id' => $_SESSION['user_id']])) {
                    $success_message = "Hasło zostało pomyślnie zmienione!";
                } else {
                    $error_message = "Wystąpił błąd podczas zmiany hasła.";
                }
            }
        }

        if (!empty($success_message)) {
            $_SESSION['profile_success'] = $success_message;
        }
        if (!empty($error_message)) {
            $_SESSION['profile_error'] = $error_message;
        }

        header('Location: index.php?page=profile');
        exit;
    }
}
