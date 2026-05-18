<div class="container py-5">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4">
            <?php $active_tab = 'orders';
            require BASE_PATH . '/views/partials/profile/sidebar.php'; ?>
        </div>

        <div class="col-md-8 col-lg-9">
            <h2 class="mb-4">Moje zamówienia</h2>

            <?php if (empty($orders)): ?>
            <?php else: ?>
                <?php foreach ($orders as $order):
                    // Pobieramy dane o statusach z OrderManager
                    $logistics = OrderManager::STATUS_DICTIONARY[$order['status']] ?? ['label' => $order['status'], 'color' => 'bg-secondary'];
                    $payment = OrderManager::PAYMENT_STATUS_DICTIONARY[$order['payment_status']] ?? ['label' => $order['payment_status'], 'color' => 'bg-light text-dark'];
                ?>
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="fw-bold">Zamówienie #<?= e($order['id']) ?></span>
                                <small class="text-muted ms-2"><?= date('d.m.Y', strtotime($order['created_at'])) ?></small>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge <?= $payment['color'] ?> border">
                                    <i class="bi bi-credit-card me-1"></i> <?= e($payment['label']) ?>
                                </span>
                                <span class="badge <?= $logistics['color'] ?>">
                                    <?= e($logistics['label']) ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-sm-8">
                                    <h5 class="mb-0 fw-bold"><?= number_format($order['total_price'], 2, ',', ' ') ?> zł</h5>
                                    <p class="text-muted small mb-0 mt-1">
                                        Metoda: <?= e(OrderManager::DELIVERY_METHODS[$order['delivery_method']] ?? $order['delivery_method']) ?>
                                    </p>
                                </div>
                                <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
                                    <?php if ($order['requires_payment']): ?>
                                        <a href="index.php?page=payment_gateway&order_id=<?= $order['id'] ?>" class="btn btn-sm btn-warning shadow-sm fw-bold me-2">
                                            <i class="bi bi-credit-card"></i> Opłać
                                        </a>
                                    <?php endif; ?>

                                    <a href="index.php?page=profile_order_details&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary shadow-sm">
                                        Szczegóły zamówienia
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
