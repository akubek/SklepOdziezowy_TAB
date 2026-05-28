<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-box-seam text-primary"></i> Panel Realizacji Zamówień</h1>
        <span class="badge bg-secondary p-2">Aktywne zamówienia: <?= count($orders) ?></span>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert alert-success py-4 text-center border-0 shadow-sm">
            <h4 class="alert-heading fw-bold mb-2">🎉 Wszystkie zamówienia zrealizowane!</h4>
            <p class="mb-0 text-muted">Nie ma obecnie żadnych nowych ani opłaconych zamówień w kolejce.</p>
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Klient</th>
                                <th class="py-3">Data zamówienia</th>
                                <th class="py-3">Wartość</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-end pe-4">Akcja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="ps-4 fw-bold">#<?= $order['id'] ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                    </td>
                                    <td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td class="fw-bold text-primary"><?= number_format($order['total_price'], 2, ',', ' ') ?> zł</td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'bg-secondary';
                                        if ($order['status'] === 'NEW') $badgeClass = 'bg-info text-dark';
                                        if ($order['status'] === 'PAID') $badgeClass = 'bg-success';
                                        if ($order['status'] === 'PROCESSING') $badgeClass = 'bg-warning text-dark';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2">
                                            <?= $order['status'] ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="index.php?page=admin_order_details&id=<?= $order['id'] ?>" class="btn btn-sm btn-primary px-3">
                                            Realizuj <i class="bi bi-arrow-right-short"></i>
                                        </a>
                                    </td>
                               </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="d-flex justify-content-between align-items-center mt-5 mb-4 border-top pt-4">
        <h3 class="h4 text-muted"><i class="bi bi-clock-history"></i> Zrealizowane zamówienia</h3>
        <span class="badge bg-light text-dark border p-2">Ostatnie 50 zamówień</span>
    </div>

    <?php if (empty($resolvedOrders)): ?>
        <div class="alert alert-light text-center border border-dashed shadow-sm text-muted py-4">
            Brak historii. Kiedy zrealizujesz pierwsze zamówienia, pojawią się one tutaj.
        </div>
    <?php else: ?>
        <div class="card border-0 shadow-sm opacity-75">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="ps-4 py-3">ID</th>
                                <th class="py-3">Klient</th>
                                <th class="py-3">Data zamówienia</th>
                                <th class="py-3">Wartość</th>
                                <th class="py-3">Status końcowy</th>
                                <th class="py-3 text-end pe-4">Akcja</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resolvedOrders as $order): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?= $order['id'] ?></td>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                    </td>
                                    <td class="text-muted"><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td class="fw-bold text-muted"><?= number_format($order['total_price'], 2, ',', ' ') ?> zł</td>
                                    <td>
                                        <?php 
                                        $badgeClass = 'bg-secondary';
                                        if ($order['status'] === 'COMPLETED') $badgeClass = 'bg-success';
                                        if ($order['status'] === 'SHIPPED') $badgeClass = 'bg-primary';
                                        if ($order['status'] === 'CANCELLED') $badgeClass = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2">
                                            <?= $order['status'] ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="index.php?page=admin_order_details&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-secondary px-3">
                                            Podgląd
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>