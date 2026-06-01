<?php
// views/profile/order_details.php

// 1. Logika tłumaczeń (taka sama jak w checkout_success)
$address = $order['shipping_address'] ?? [];

$statusData = OrderManager::STATUS_DICTIONARY[$order['status']] ?? ['label' => $order['status'], 'color' => 'bg-secondary'];
$deliveryName = OrderManager::DELIVERY_METHODS[$order['delivery_method']] ?? $order['delivery_method'];
$paymentName = OrderManager::PAYMENT_METHODS[$order['payment_method']] ?? $order['payment_method'];

?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4">
            <?php
            $active_tab = 'orders';
            require BASE_PATH . '/views/partials/profile/sidebar.php';
            ?>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="index.php?page=profile_orders">Zamówienia</a></li>
                            <li class="breadcrumb-item active">Szczegóły #<?= e($order['id']) ?></li>
                        </ol>
                    </nav>
                    <h2 class="fw-bold mb-0">Zamówienie #<?= e($order['id']) ?></h2>
                </div>
                <span class="badge <?= $statusData['color'] ?> fs-6 px-3 py-2">
                    <?= e($statusData['label']) ?>
                </span>
            </div>

            <?php if ($requires_payment): ?>
                <div class="card border-warning mb-4 shadow-sm bg-warning bg-opacity-10">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center p-4">
                        <div class="mb-3 mb-md-0">
                            <h5 class="mb-1 fw-bold text-dark">
                                <i class="bi bi-exclamation-circle text-warning me-2"></i>
                                Oczekiwanie na płatność
                            </h5>
                            <p class="mb-0 text-muted">
                                Twoje zamówienie nie zostało jeszcze opłacone. Opłać je, abyśmy mogli rozpocząć realizację.
                            </p>
                        </div>

                        <a href="index.php?page=payment_gateway&order_id=<?= $order['id'] ?>" class="btn btn-warning fw-bold px-4 shadow-sm">
                            <i class="bi bi-credit-card-2-front me-2"></i> Opłać teraz
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small text-uppercase fw-bold">Metoda dostawy i płatności</h6>
                            <p class="mb-1"><strong>Dostawa:</strong> <?= e($deliveryName) ?></p>
                            <p class="mb-0"><strong>Płatność:</strong> <?= e($paymentName) ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted small text-uppercase fw-bold">Adres dostawy</h6>
                            <p class="mb-0">
                                <strong><?= e($address['first_name'] ?? '') ?> <?= e($address['last_name'] ?? '') ?></strong><br>
                                <?php if ($order['delivery_method'] === 'courier'): ?>
                                    <?= e($address['street'] ?? '') ?><br>
                                    <?= e($address['zip_code'] ?? '') ?> <?= e($address['city'] ?? '') ?><br>
                                <?php elseif ($order['delivery_method'] === 'paczkomat'): ?>
                                    Paczkomat: <strong><?= e($address['paczkomat_code'] ?? '') ?></strong><br>
                                <?php else: ?>
                                    Odbiór osobisty w punkcie<br>
                                <?php endif; ?>
                                Tel: <?= e($address['phone'] ?? 'Brak') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Zamówione produkty</h5>

                    <?php
                    $items = $order['items'];
                    $isSuccessPage = true; // Flaga, jeśli partial jej wymaga do formatowania
                    // Używamy nowej ścieżki do partiala
                    include BASE_PATH . '/views/partials/order_items_list.php';
                    ?>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <span class="fs-5 fw-bold">Wartość całkowita:</span>
                        <span class="fs-4 fw-bold text-primary"><?= number_format($order['total_price'], 2, ',', ' ') ?> zł</span>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="index.php?page=profile_orders" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Wróć do listy zamówień
                </a>
            </div>

            <?php if ($order['status'] === 'NEW'): // Można anulować tylko nowe zamówienia 
            ?>
                <form action="index.php?page=profile_order_cancel" method="POST" onsubmit="return confirm('Czy na pewno chcesz anulować to zamówienie? Tej operacji nie można cofnąć.');">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <button type="submit" class="btn btn-outline-danger mt-3">Anuluj zamówienie</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
