<?php
// src/managers/AuthManager.php
class AuthManager
{
    private $userManager;

    public function __construct($userManager)
    {
        $this->userManager = $userManager;
    }

    public function login($email, $password)
    {
        $user = $this->userManager->getUserByEmail($email);
        if ($user && $this->verifyCurrentPassword($user['id'], $password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            return true;
        }
        return false;
    }

    public function verifyCurrentPassword($userId, $password)
    {
        // Pobieramy tylko potrzebny hash z bazy
        $hash = $this->userManager->getPasswordHash($userId);

        if ($hash && password_verify($password, $hash)) {
            return true;
        }
        return false;
    }
}
