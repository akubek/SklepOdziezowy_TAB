<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-boxes text-primary"></i> Zarządzanie Magazynem</h1>
        <div>
            <a href="index.php?page=admin_category_add" class="btn btn-outline-secondary me-2">
                <i class="bi bi-folder-plus"></i> Dodaj kategorię
            </a>
            <a href="index.php?page=admin_product_add" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Dodaj produkt
            </a>
        </div>
    </div>

    <ul class="nav nav-tabs mb-4" id="inventoryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab" aria-controls="products" aria-selected="true">
                <i class="bi bi-box-seam me-1"></i> Produkty (<?= count($products) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="false">
                <i class="bi bi-tags me-1"></i> Kategorie (<?= count($categories) ?>)
            </button>
        </li>
    </ul>

    <div class="tab-content" id="inventoryTabsContent">
        
        <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">ID</th>
                                    <th class="py-3">Produkt</th>
                                    <th class="py-3">Kategoria</th>
                                    <th class="py-3 text-end">Cena bazowa</th>
                                    <th class="py-3 text-center">Stan Mag. (Suma)</th>
                                    <th class="py-3 text-end pe-4">Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)): ?>
                                    <tr><td colspan="6" class="text-center py-4 text-muted">Brak produktów w bazie.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($products as $p): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-muted">#<?= $p['id'] ?></td>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($p['name']) ?></div>
                                                <?php if ($p['brand_name']): ?>
                                                    <small class="text-muted">Marka: <?= htmlspecialchars($p['brand_name']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary rounded-pill px-3 py-2">
                                                    <?= htmlspecialchars($p['category_name'] ?? 'Brak') ?>
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">
                                                <?= number_format($p['base_price'], 2, ',', ' ') ?> zł
                                            </td>
                                            <td class="text-center">
                                                <?php $stockClass = $p['total_stock'] > 0 ? 'text-success fw-bold' : 'text-danger fw-bold'; ?>
                                                <span class="<?= $stockClass ?>"><?= $p['total_stock'] ?> szt.</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="index.php?page=admin_variants&product_id=<?= $p['id'] ?>" class="btn btn-sm btn-info text-white me-1" title="Zarządzaj Wariantami">
                                                    <i class="bi bi-layers"></i> Warianty
                                                </a>
                                                <a href="index.php?page=admin_product_edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary me-1">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="index.php?page=admin_product_delete" method="POST" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten produkt oraz jego warianty?');">
                                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Usuń produkt">
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

            <?php if ($totalPages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="index.php?page=admin_inventory&p=<?= $currentPage - 1 ?>">Poprzednia</a>
                        </li>
                        
                        <li class="page-item disabled">
                            <span class="page-link">Strona <?= $currentPage ?> z <?= $totalPages ?></span>
                        </li>
                        
                        <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="index.php?page=admin_inventory&p=<?= $currentPage + 1 ?>">Następna</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div> <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-3">ID</th>
                                    <th class="py-3">Nazwa Kategorii</th>
                                    <th class="py-3">Kategoria Nadrzędna</th>
                                    <th class="py-3 text-end pe-4">Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr><td colspan="4" class="text-center py-4 text-muted">Brak kategorii w bazie.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $c): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-muted">#<?= $c['id'] ?></td>
                                            <td class="fw-bold"><?= htmlspecialchars($c['name']) ?></td>
                                            <td>
                                                <?php if ($c['parent_name']): ?>
                                                    <span class="text-muted"><i class="bi bi-arrow-return-right"></i> <?= htmlspecialchars($c['parent_name']) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-dark border">Główna kategoria</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="index.php?page=admin_category_edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-primary me-1">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="index.php?page=admin_category_delete" method="POST" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć tę kategorię?');">
                                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Usuń">
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
        </div> </div>
</div>