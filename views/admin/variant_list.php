<div class="container my-5">
    <div class="mb-4">
        <a href="index.php?page=admin_inventory" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left"></i> Powrót do magazynu
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Warianty produktu: <span class="text-primary"><?= htmlspecialchars($product['name']) ?></span></h1>
            <small class="text-muted">Cena bazowa produktu: <?= number_format($product['base_price'], 2, ',', ' ') ?> zł</small>
        </div>
        <a href="index.php?page=admin_variant_add&product_id=<?= $product['id'] ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Dodaj Wariant
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">SKU</th>
                            <th class="py-3">Atrybuty (JSON)</th>
                            <th class="py-3 text-end">Cena wariantu</th>
                            <th class="py-3 text-center">Ilość w mag.</th>
                            <th class="py-3 text-end pe-4">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($variants)): ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Ten produkt nie ma jeszcze wariantów.</td></tr>
                        <?php else: ?>
                            <?php foreach ($variants as $v): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= htmlspecialchars($v['sku']) ?></td>
                                    <td><code class="text-secondary"><?= htmlspecialchars($v['attributes']) ?></code></td>
                                    <td class="text-end fw-bold"><?= number_format($v['variant_price'], 2, ',', ' ') ?> zł</td>
                                    <td class="text-center">
                                        <?php $stockClass = $v['stock_quantity'] > 0 ? 'text-success fw-bold' : 'text-danger fw-bold'; ?>
                                        <span class="<?= $stockClass ?>"><?= $v['stock_quantity'] ?> szt.</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="index.php?page=admin_variant_edit&id=<?= $v['id'] ?>&product_id=<?= $product['id'] ?>" class="btn btn-sm btn-primary me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="index.php?page=admin_variant_delete" method="POST" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten wariant?');">
                                            <input type="hidden" name="id" value="<?= $v['id'] ?>">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
