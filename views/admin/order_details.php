<?php
// Dekodujemy adres wysyłki z formatu JSONB
$shipping = json_decode($order['shipping_address'], true) ?? [];

// 1. Słowniki tłumaczeń dla metod dostawy i płatności
$deliveryMethods = [
    'pickup'           => 'Odbiór osobisty',
    'paczkomat'        => 'Paczkomat InPost',
    'courier'          => 'Przesyłka kurierska'
];

$paymentMethods = [
    'cash_on_delivery' => 'Przy odbiorze (Za pobraniem)',
    'bank_transfer'    => 'Przelew tradycyjny',
    'stripe'           => 'Karta płatnicza (Stripe)',
    'blik'             => 'BLIK'
];

$deliveryName = $deliveryMethods[$order['delivery_method']] ?? $order['delivery_method'];
$paymentName  = $paymentMethods[$order['payment_method']] ?? $order['payment_method'];
?>

<div class="container my-5">
    <div class="mb-4">
        <a href="index.php?page=admin_orders" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left"></i> Powrót do listy zamówień
        </a>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            🚀 Status zamówienia został zaktualizowany pomyślnie!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h2 class="h4 mb-3 fw-bold">Zamówienie #<?= $order['id'] ?></h2>
                    <p class="text-muted mb-0">Złożone dnia: <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Zakupione produkty</h5>
                </div>
                <div class="card-body px-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th>Produkt (Wariant)</th>
                                    <th class="text-center">Ilość</th>
                                    <th class="text-end">Cena jedn.</th>
                                    <th class="text-end">Suma</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($item['variant_name']) ?></div>
                                            <small class="text-muted">ID Wariantu: <?= $item['variant_id'] ?></small>
                                        </td>
                                        <td class="text-center fw-bold">x<?= $item['quantity'] ?></td>
                                        <td class="text-end"><?= number_format($item['unit_price'], 2, ',', ' ') ?> zł</td>
                                        <td class="text-end fw-bold text-primary">
                                            <?= number_format($item['unit_price'] * $item['quantity'], 2, ',', ' ') ?> zł
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr class="border-0">
                                    <td colspan="2" class="border-0"></td>
                                    <td class="text-end fw-bold fs-5 border-0 pt-4">Razem:</td>
                                    <td class="text-end fw-bold fs-5 text-primary border-0 pt-4">
                                        <?= number_format($order['total_price'], 2, ',', ' ') ?> zł
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if ($order['wants_invoice']): ?>
                <div class="card border-0 shadow-sm bg-light mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-danger mb-3"><i class="bi bi-file-earmark-text"></i> Klient prosi o fakturę VAT</h5>
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block">Nazwa firmy:</small>
                                <strong><?= htmlspecialchars($order['invoice_company_name']) ?></strong>
                            </div>
                            <div class="col-sm-6 mb-2">
                                <small class="text-muted d-block">NIP:</small>
                                <strong><?= htmlspecialchars($order['invoice_nip']) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt text-primary"></i> Adres dostawy</h5>
                    <p class="mb-1 fw-bold"><?= htmlspecialchars($order['client_first_name'] . ' ' . $order['client_last_name']) ?></p>
                    
                    <?php if (!empty($shipping['paczkomat_code'])): ?>
                        <div class="alert alert-warning p-3 mt-3 border-0 shadow-sm">
                            <small class="d-block text-muted mb-1 fw-bold text-uppercase">Wybrany Paczkomat:</small>
                            <span class="fs-4 fw-bold text-dark">
                                <i class="bi bi-box-seam me-1"></i> <?= htmlspecialchars($shipping['paczkomat_code']) ?>
                            </span>
                        </div>
                    <?php else: ?>
                        <p class="mb-1"><?= htmlspecialchars($shipping['street'] ?? '') ?> <?= htmlspecialchars($shipping['home_number'] ?? '') ?></p>
                        <p class="mb-1"><?= htmlspecialchars($shipping['zip_code'] ?? '') ?> <?= htmlspecialchars($shipping['city'] ?? '') ?></p>
                    <?php endif; ?>
                    
                    <hr class="my-3 text-muted">
                    
                    <small class="text-muted d-block mb-1">Metoda dostawy:</small>
                    <span class="badge bg-light text-dark border p-2 mb-2 w-100 text-start fs-6">
                        <i class="bi bi-truck me-1"></i> <?= htmlspecialchars($deliveryName) ?>
                    </span>
                    
                    <small class="text-muted d-block mb-1">Metoda płatności:</small>
                    <span class="badge bg-light text-dark border p-2 w-100 text-start fs-6">
                        <i class="bi bi-credit-card me-1"></i> <?= htmlspecialchars($paymentName) ?>
                    </span>
                </div>
            </div>
            </div>

            <div class="card border-0 shadow-sm border-top border-primary border-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-gear-fill text-primary"></i> Zarządzanie zamówieniem</h5>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block">Obecny status:</small>
                        <strong class="fs-5 text-uppercase"><?= $order['status'] ?></strong>
                    </div>

                    <form action="index.php?page=admin_order_update" method="POST">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Zmień status na:</label>
                            <select name="status" class="form-select" required>
                                <option value="PROCESSING" <?= $order['status'] === 'PROCESSING' ? 'selected' : '' ?>>PROCESSING (W trakcie kompletowania)</option>
                                <option value="SHIPPED" <?= $order['status'] === 'SHIPPED' ? 'selected' : '' ?>>SHIPPED (Wysłane / Przekazane kurierowi)</option>
                                <option value="COMPLETED" <?= $order['status'] === 'COMPLETED' ? 'selected' : '' ?>>COMPLETED (Zrealizowane końcowo)</option>
                                <option value="CANCELLED" <?= $order['status'] === 'CANCELLED' ? 'selected' : '' ?>>CANCELLED (Anulowane)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            Zapisz i aktualizuj
                        </button>
                    </form>

                    <?php if ($order['employee_id']): ?>
                        <div class="mt-4 pt-3 border-top small text-muted">
                            <i class="bi bi-person-check"></i> Ostatnio przetwarzane przez:<br>
                            <strong><?= htmlspecialchars($order['emp_first_name'] . ' ' . $order['emp_last_name']) ?></strong> (ID: <?= $order['employee_id'] ?>)
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>