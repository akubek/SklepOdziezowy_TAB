<?php require_once BASE_PATH . '/views/partials/breadcrumb.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 fw-bold m-0"><?= htmlspecialchars($currentCategory['name']) ?></h1>
    <div class="d-flex align-items-center gap-3">
        <select class="form-select form-select-sm" id="sort-select" style="width: auto;">
            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Najnowsze</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Cena: od najniższej</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Cena: od najwyższej</option>
            <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Nazwa: A-Z</option>
        </select>
        <span class="text-muted text-nowrap">Znaleziono produktów: <?= count($products) ?></span>
    </div>
</div>
<?php $showCategoryBadge = false; ?>
<?php require BASE_PATH . '/views/partials/product_grid.php'; ?>
