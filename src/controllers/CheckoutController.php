<?php
// controllers/CheckoutController.php

class CheckoutController
{
    private $orderManager;
    private $cartManager;
    private $userManager;

    public function __construct($orderManager, $cartManager, $userManager)
    {
        $this->orderManager = $orderManager;
        $this->cartManager = $cartManager;
        $this->userManager = $userManager;
    }
    public function start()

    {
        $this->requireValidCart(); //we ignore the returned value

        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?page=checkout_form');
            exit;
        }

        renderView('checkout_auth_gate');
    }

    public function handleCheckout()
    {
        $cart = $this->requireValidCart();
        $summary = $this->cartManager->getCartSummary($cart);
        $currentDbTotal = $summary['totalPrice'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Idempotency Token
            $postedToken = $_POST['checkout_token'] ?? '';
            $sessionToken = $_SESSION['checkout_token'] ?? '';

            if (empty($postedToken) || $postedToken !== $sessionToken) {
                // Drugie żądanie wpadnie tutaj, bo token w sesji zostanie usunięty przez pierwsze żądanie!
                $this->redirectWithError("Przetwarzamy już to żądanie lub formularz wygasł. Sprawdź koszyk.");
            }
            // Natychmiastowe "spalenie" tokenu w sesji - drugie żądanie (jeśli czeka) od razu na tym polegnie
            unset($_SESSION['checkout_token']);

            $expectedTotal = $_SESSION['checkout_expected_total'] ?? 0;
            if (abs($currentDbTotal - $expectedTotal) > 0.01) {
                $this->redirectWithError("Ceny uległy zmianie! Zaktualizowaliśmy koszyk.", "cart");
            }

            $paymentMethod = $_POST['payment_method'] ?? '';
            $allowedPayments = ['cash_on_delivery', 'online', 'blik', 'transfer', 'payu', 'bank_transfer']; // Dopasuj do swoich wartości z formularza!
            if (!in_array($paymentMethod, $allowedPayments, true)) {
                $this->redirectWithError("Wybrano nieprawidłową metodę płatności!");
            }

            $deliveryMethod = $_POST['delivery_method'] ?? '';
            $allowedDeliveries = ['pickup', 'courier', 'paczkomat']; // Dopasuj do swojego HTML
            if (!in_array($deliveryMethod, $allowedDeliveries, true)) {
                $this->redirectWithError("Wybrano nieprawidłową metodę dostawy!");
            }

            $shippingData = $_POST['shipping'] ?? [];

            // Zabezpieczenie przed podaniem stringa zamiast tablicy w shipping
            if (!is_array($shippingData)) {
                $shippingData = [];
            }

            // Basic sanitization
            foreach ($shippingData as $key => $value) {
                $shippingData[$key] = trim(strip_tags((string)$value));
            }

            // check email validity
            $finalEmail = '';
            if (isset($_SESSION['user_id'])) {
                // logged in - get from database
                $currentUser = $this->userManager->getUserById($_SESSION['user_id']);
                $finalEmail = $currentUser['email'] ?? '';
                unset($shippingData['email']); // Ignorujemy to, co mogło przyjść z frontu
            } else {
                // guest - check e-mail format
                $guestEmail = $shippingData['email'] ?? '';
                if (!filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
                    $this->redirectWithError("Podano niepoprawny adres e-mail!");
                }
                $finalEmail = $guestEmail;
            }

            // normlize and validate phone
            $phone = preg_replace('/[^0-9]/', '', $shippingData['phone'] ?? '');

            if (strlen($phone) !== 9) {
                $this->redirectWithError("Podano niepoprawny numer telefonu. Wymagane jest 9 cyfr.");
            }
            $shippingData['phone'] = $phone; // Zapisujemy z powrotem czysty numer do tablicy

            // Sprawdzenie długości email
            if (mb_strlen($finalEmail) > 255) {
                $this->redirectWithError("Adres e-mail jest zbyt długi (maksymalnie 255 znaków).");
            }

            // Sprawdzenie długości imienia i nazwiska
            if (mb_strlen($shippingData['first_name']) > 50 || mb_strlen($shippingData['last_name']) > 50) {
                $this->redirectWithError("Imię i nazwisko mogą mieć maksymalnie 50 znaków.");
            }

            if (empty($shippingData['first_name']) || empty($shippingData['last_name'])) {
                $this->redirectWithError("Imię i nazwisko są wymagane!");
            }

            if (empty($shippingData['first_name']) || empty($shippingData['last_name'])) {
                $this->redirectWithError("Imię i nazwisko są wymagane!");
            }

            if ($deliveryMethod === 'courier') {
                if (empty($shippingData['street']) || empty($shippingData['city'])) {
                    $this->redirectWithError("Proszę podać pełny adres (ulica i miasto).");
                }
                if (mb_strlen($shippingData['street']) > 100) {
                    $this->redirectWithError("Adres ulicy może mieć maksymalnie 100 znaków.");
                }

                if (mb_strlen($shippingData['city']) > 50) {
                    $this->redirectWithError("Nazwa miasta może mieć maksymalnie 50 znaków.");
                }
                if (!preg_match('/^[0-9]{2}-[0-9]{3}$/', $shippingData['zip_code'] ?? '')) {
                    $this->redirectWithError("Niepoprawny format kodu pocztowego (wymagany: XX-XXX).");
                }
            } elseif ($deliveryMethod === 'paczkomat') {
                $paczkomatCode = strtoupper(trim($shippingData['paczkomat_code'] ?? ''));
                $shippingData['paczkomat_code'] = $paczkomatCode;

                if (empty($paczkomat_code)) {
                    $this->redirectWithError("Proszę podać kod Paczkomatu.");
                }

                if (!preg_match('/^[A-Z]{3}[0-9A-Z]{3,4}$/', $paczkomatCode)) {
                    $this->redirectWithError("Nieprawidłowy format kodu Paczkomatu (np. WAW01M).");
                }
            }

            try {
                // created guest or user found in db by email
                $finalUserId = null;

                if (isset($_SESSION['user_id'])) {
                    // Scenariusz A: Zalogowany
                    $finalUserId = $_SESSION['user_id'];
                } else {
                    // Scenariusz B: Gość
                    $existingUser = $this->userManager->getUserByEmail($finalEmail);

                    if ($existingUser) {
                        $finalUserId = $existingUser['id'];
                    } else {
                        // create guest account (shadow account) if email was not found in database
                        $finalUserId = $this->userManager->createGuestUser(
                            $finalEmail,
                            $shippingData['first_name'],
                            $shippingData['last_name'],
                            $shippingData['phone']
                        );
                    }
                }

                //create order 
                $orderId = $this->orderManager->createOrder(
                    $finalUserId,
                    $expectedTotal,
                    $deliveryMethod,
                    $paymentMethod,
                    $shippingData,
                    $summary['items']
                );

                // success - save last order to display to user
                $_SESSION['last_order_id'] = $orderId;
                unset($_SESSION['checkout_expected_total']);
                setcookie('cart', '', time() - 3600, '/');

                header("Location: index.php?page=checkout_success");
                exit;
            } catch (PriceChangedException $e) {
                error_log("Błąd kasy: " . $e->getMessage());
                $this->redirectWithError($e->getMessage() . " Przepraszamy, prosimy o ponowną weryfikację koszyka.", 'cart');
                exit;
            } catch (ProductUnavailableException $e) {
                error_log("Błąd kasy: " . $e->getMessage());
                $this->redirectWithError($e->getMessage() . " Prosimy o usunięcie niedostępnych produktów z koszyka przed kontynuacją.", 'cart');
                exit;
            } catch (Exception $e) {
                error_log("Błąd kasy: " . $e->getMessage());
                $this->redirectWithError("Wystąpił krytyczny problem z systemem. Spróbuj ponownie.");
            } finally {
                //if needed add cleaning here
            }
        }

        // ==========================================
        // 3. OBSŁUGA ŻĄDANIA GET (Zwykłe wejście na stronę - renderowanie formularza)
        // ==========================================
        $_SESSION['checkout_expected_total'] = $currentDbTotal;

        $checkoutToken = bin2hex(random_bytes(32));
        $_SESSION['checkout_token'] = $checkoutToken;
        $errorMessage = $_SESSION['flash_error'] ?? '';
        unset($_SESSION['flash_error']);

        $currentUser = null;
        if (isset($_SESSION['user_id'])) {
            $currentUser = $this->userManager->getUserById($_SESSION['user_id']);
        }

        renderView('checkout_form', [
            'errorMessage' => $errorMessage,
            'items' => $summary['items'],
            'totalToPay' => $currentDbTotal,
            'currentUser' => $currentUser,
            'checkoutToken' => $checkoutToken
        ]);
    }

    public function showSuccess()
    {
        // Pobieramy ID zamówienia z sesji (ustawione po udanym zapisie)
        $orderId = $_SESSION['last_order_id'] ?? null;

        // Jeśli ktoś wpisze adres z palca, a nie ma przypisanego zamówienia -> na stronę główną
        if (!$orderId) {
            header("Location: index.php");
            exit;
        }

        // Pobieramy świeże dane z bazy za pomocą OrderManagera
        $orderData = $this->orderManager->getOrderSummary($orderId);

        // Jeśli zamówienie z jakiegoś powodu zniknęło z bazy
        if (!$orderData) {
            unset($_SESSION['last_order_id']); // Sprzątamy osierocone ID
            header("Location: index.php");
            exit;
        }

        // UWAGA: Świadomie NIE robimy tutaj unset($_SESSION['last_order_id']); 
        // Pozwala to użytkownikowi na odświeżenie strony (F5) i spokojne skopiowanie numeru zamówienia.
        // Zmienna zniknie automatycznie po zamknięciu przeglądarki lub wylogowaniu.

        renderView('checkout_success', [
            'order' => $orderData
        ]);
    }

    private function redirectWithError(string $message, string $page = 'checkout_form'): void
    {
        unset($_SESSION['processing_order_time']);

        // Opcjonalnie: Tutaj w przyszłości możesz zapisywać do sesji $_POST['shipping'], 
        // żeby zwrócić klientowi wpisane przez niego dane, aby nie musiał wypełniać formularza od nowa!

        $_SESSION['flash_error'] = $message;
        header("Location: index.php?page=" . $page);
        exit;
    }

    private function requireValidCart(): array
    {
        // Pobieramy i dekodujemy surowe ciasteczko
        $rawCart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
        if (!is_array($rawCart)) {
            $rawCart = [];
        }

        $cleanCart = [];

        // Rygorystyczna sanitaryzacja
        foreach ($rawCart as $variantId => $qty) {
            $qtyInt = (int)$qty;
            if ($qtyInt > 0) {
                $cleanCart[(int)$variantId] = $qtyInt;
            }
        }

        if (empty($cleanCart)) {
            // Opcjonalnie: od razu usuwamy zepsute ciasteczko w przeglądarce klienta
            setcookie('cart', '', time() - 3600, '/');
            $this->redirectWithError("Twój koszyk jest pusty lub zawierał nieprawidłowe dane.", "cart");
        }

        // Zwracamy w 100% bezpieczną tablicę [variant_id => quantity]
        return $cleanCart;
    }
}
