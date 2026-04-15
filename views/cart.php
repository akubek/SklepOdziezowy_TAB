<div class="py-4">
    <h1 class="mb-4">Twój Koszyk</h1>

    <?php if (empty($items)): ?>
        <div class="alert alert-info py-5 text-center">
            <p class="lead">Twój koszyk jest pusty.</p>
            <a href="index.php?page=home" class="btn btn-primary">Zacznij zakupy</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="cart-table">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produkt</th>
                                    <th>Cena</th>
                                    <th>Ilość</th>
                                    <th class="text-end">Suma</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr data-variant-id="<?= $item['variant_id'] ?>" data-price="<?= $item['unit_price'] ?>" data-stock="<?= $item['stock'] ?>">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= $item['image'] ?>" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <div class="fw-bold"><?= htmlspecialchars($item['name']) ?></div>
                                                    <small class="text-muted">
                                                        <?= implode(', ', array_map(fn($k, $v) => "$k: $v", array_keys($item['attributes']), $item['attributes'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($item['unit_price'], 2, ',', ' ') ?> zł</td>
                                        <td>
                                            <div class="input-group input-group-sm" style="width: 100px;">
                                                <button class="btn btn-outline-secondary btn-minus">-</button>
                                                <input type="text" class="form-control text-center qty-input" value="<?= $item['quantity'] ?>" readonly>
                                                <button class="btn btn-outline-secondary btn-plus">+</button>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold subtotal"><?= number_format($item['subtotal'], 2, ',', ' ') ?> zł</td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-link text-danger btn-remove">Usuń</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <button class="btn btn-outline-danger" id="clear-cart">Opróżnij koszyk</button>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 p-4">
                    <h5 class="mb-4">Podsumowanie</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Suma częściowa</span>
                        <span id="cart-total"><?= number_format($totalPrice, 2, ',', ' ') ?> zł</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span>Dostawa</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4 fw-bold fs-5">
                        <span>Łącznie</span>
                        <span id="cart-grand-total"><?= number_format($totalPrice, 2, ',', ' ') ?> zł</span>
                    </div>
                    <a href="index.php?page=checkout" class="btn btn-primary btn-lg w-100">Przejdź do kasy</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
