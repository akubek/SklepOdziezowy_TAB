<?php
// Wyciągamy adres dla wygody
$address = $order['shipping_address'] ?? [];

// Słowniki tłumaczeń (Mapowanie technicznych kluczy na ładne polskie nazwy)
$deliveryLabels = [
    'pickup'    => 'Odbiór osobisty',
    'courier'   => 'Kurier',
    'paczkomat' => 'Paczkomat InPost'
];

$paymentLabels = [
    'cash_on_delivery'  => 'Płatność przy odbiorze',
    'payu'              => 'Płatność online (PayU)',
    'bank_transfer'     => 'Przelew tradycyjny',


    'online'            => 'Płatność online',
    'blik'              => 'BLIK',
    'transfer'          => 'Przelew tradycyjny'
];

// Pobieramy przetłumaczone wartości (z tzw. fallbackiem - jeśli dodasz w przyszłości 
// nową metodę i zapomnisz jej tu dopisać, wyświetli się po prostu jej klucz, a strona się nie zepsuje)
$deliveryName = $deliveryLabels[$order['delivery_method']] ?? strtoupper($order['delivery_method']);
$paymentName  = $paymentLabels[$order['payment_method']] ?? strtoupper($order['payment_method']);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Nagłówek z Ikoną Sukcesu -->
            <div class="text-center mb-5">
                <div class="text-success mb-3">
                    <!-- Tu jest ikona - zaraz to omówimy! -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg>
                </div>
                <h1 class="display-5 fw-bold">Dziękujemy za zamówienie!</h1>
                <p class="lead mt-3">
                    Twoje zamówienie zostało przyjęte do realizacji.<br>
                    Numer zamówienia: <strong class="fs-4">#<?= e($order['id']) ?></strong>
                </p>
            </div>

            <!-- Podsumowanie danych -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="card-title border-bottom pb-2 mb-3">Szczegóły dostawy</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Metoda dostawy</h6>
                            <p class="mb-0 fw-bold"><?= e($deliveryName) ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Metoda płatności</h6>
                            <p class="mb-0 fw-bold"><?= e($paymentName) ?></p>
                        </div>

                        <div class="col-12 mt-2">
                            <h6 class="text-muted mb-1">Dane adresowe</h6>
                            <p class="mb-0">
                                <?= e($address['first_name'] ?? '') ?> <?= e($address['last_name'] ?? '') ?><br>
                                <?php if ($order['delivery_method'] === 'courier'): ?>
                                    <?= e($address['street'] ?? '') ?><br>
                                    <?= e($address['zip_code'] ?? '') ?> <?= e($address['city'] ?? '') ?><br>
                                <?php elseif ($order['delivery_method'] === 'paczkomat'): ?>
                                    Paczkomat: <strong><?= e($address['paczkomat_code'] ?? '') ?></strong><br>
                                <?php endif; ?>
                                Tel: <?= e($address['phone'] ?? '') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista kupionych produktów -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="card-title border-bottom pb-2 mb-3">Kupione produkty</h5>

                    <?php
                    // Przekazujemy items z zamówienia i ustawiamy flagę
                    $items = $order['items'];
                    $isSuccessPage = true;
                    include BASE_PATH . '/views/partials/order_items_list.php';
                    ?>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <span class="fs-5 fw-bold">Razem do zapłaty:</span>
                        <span class="fs-4 fw-bold text-primary"><?= number_format($order['total_price'], 2) ?> zł</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="index.php" class="btn btn-outline-secondary btn-lg">Wróć do strony głównej</a>
            </div>

        </div>
    </div>
</div>
