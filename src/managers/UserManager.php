<?php
class UserManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, email, first_name, last_name, phone_number, role, birth_date, gender, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Zwraca tablicę z danymi lub false
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT id, email, first_name, last_name, phone_number, role, birth_date, gender, created_at FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPasswordHash($userId)
    {
        $stmt = $this->pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function createUser($firstName, $lastName, $email, $passwordHash, $role = 'CLIENT')
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (first_name, last_name, email, password_hash, role) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$firstName, $lastName, $email, $passwordHash, $role]);

        return $this->pdo->lastInsertId();
    }

    public function createGuestUser($email, $firstName, $lastName, $phone)
    {
        // Tworzymy użytkownika z rolą 'guest' (lub 'user') i bez hasła (lub z losowym hash)
        $stmt = $this->pdo->prepare("
            INSERT INTO users (email, first_name, last_name, phone_number, role) 
            VALUES (?, ?, ?, ?, 'GUEST')
        ");
        $stmt->execute([$email, $firstName, $lastName, $phone]);

        return $this->pdo->lastInsertId();
    }

    public function upgradeGuestToClient($userId, $firstName, $lastName, $passwordHash)
    {
        $user = $this->getUserById($userId);
        if (!$user || $user['role'] !== 'GUEST') {
            return false;
        }
        $saveStmt = $this->pdo->prepare("
                                UPDATE users 
                                SET first_name = :first_name, 
                                    last_name = :last_name, 
                                    password_hash = :password_hash, 
                                    role = 'CLIENT' 
                                WHERE id = :id
                            ");
        return $saveStmt->execute([
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'password_hash' => $passwordHash,
            'id'            => $userId
        ]);
    }

    public function updateBasicData($id, $firstName, $lastName, $email)
    {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, email = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$firstName, $lastName, $email, $id]);
    }

    public function updatePassword($id, $newHash)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        return $stmt->execute([$newHash, $id]);
    }

    /**
     * Sprawdza, czy podany adres e-mail jest już zajęty przez INNEGO użytkownika.
     * Używane do walidacji przy edycji profilu.
     */
    public function isEmailTaken($email, $excludeUserId = null)
    {
        $sql = "SELECT 1 FROM users WHERE email = ? AND role != 'GUEST'";
        $params = [$email];

        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    /**
     * Aktualizuje dane profilowe użytkownika.
     */
    public function updateProfile($userId, $firstName, $lastName, $email, $phone, $birthDate, $gender)
    {
        // Używamy nazwanych parametrów (:nazwa) zamiast znaków zapytania (?), 
        // ponieważ przy tak wielu zmiennych zapobiega to pomyłkom w kolejności.
        $sql = "UPDATE users 
                SET first_name = :first_name, 
                    last_name = :last_name, 
                    email = :email, 
                    phone_number = :phone_number, 
                    birth_date = :birth_date, 
                    gender = :gender
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':first_name'   => $firstName,
            ':last_name'    => $lastName,
            ':email'        => $email,
            ':phone_number' => $phone ?: null,
            ':birth_date'   => $birthDate ?: null,
            ':gender'       => $gender ?: null,
            ':id'           => $userId
        ]);
    }
}
