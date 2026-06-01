<?php
// controllers/CheckoutController.php

class CheckoutController
{
    public function __construct() {}

    public function start()
    {
        $this->requireValidCart();

        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=checkout_form');
            exit;
        }

        renderView('checkout_auth_gate');
    }

    public function showForm()
    {
        $this->requireValidCart();

        $errors = [];
        $success = false;
        
        // Pre-fill if logged in
        $formData = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'street' => '',
            'city' => '',
            'zip_code' => '',
            'paczkomat' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = [
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name'  => trim($_POST['last_name'] ?? ''),
                'email'      => trim($_POST['email'] ?? ''),
                'street'     => trim($_POST['street'] ?? ''),
                'city'       => trim($_POST['city'] ?? ''),
                'zip_code'   => trim($_POST['zip_code'] ?? ''),
                'paczkomat'  => trim($_POST['paczkomat'] ?? '')
            ];

            // Server-side validation
            if (empty($formData['first_name']) || mb_strlen($formData['first_name']) > 32) {
                $errors[] = "Imię jest wymagane i nie może przekraczać 32 znaków.";
            }
            if (empty($formData['last_name']) || mb_strlen($formData['last_name']) > 32) {
                $errors[] = "Nazwisko jest wymagane i nie może przekraczać 32 znaków.";
            }
            if (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Podaj poprawny adres e-mail.";
            }
            if (empty($formData['street']) || mb_strlen($formData['street']) > 64) {
                $errors[] = "Ulica jest wymagana i nie może przekraczać 64 znaków.";
            }
            if (empty($formData['city']) || mb_strlen($formData['city']) > 32) {
                $errors[] = "Miasto jest wymagane i nie może przekraczać 32 znaków.";
            }
            if (!preg_match('/^\d{2}-\d{3}$/', $formData['zip_code'])) {
                $errors[] = "Podaj poprawny kod pocztowy (00-000).";
            }
            if (!preg_match('/^[A-Z]{3}\d{2,5}[A-Z]?$/i', $formData['paczkomat'])) {
                $errors[] = "Podaj poprawny kod Paczkomatu (np. WAW01A).";
            }

            if (empty($errors)) {
                // Here we would normally save the order to database
                // For now, let's clear the cart and show success
                setcookie('cart', '', time() - 3600, '/');
                $success = true;
            }
        }

        renderView('checkout_form', [
            'title' => 'Dane Dostawy',
            'errors' => $errors,
            'success' => $success,
            'formData' => $formData
        ]);
    }

    private function requireValidCart(): void
    {
        $cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
        if (empty($cart)) {
            header('Location: index.php?page=cart');
            exit;
        }
    }
}
