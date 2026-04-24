<?php require_once BASE_PATH . '/views/partials/breadcrumb.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 fw-bold m-0"><?= htmlspecialchars($currentCategory['name']) ?></h1>
    <span class="text-muted">Znaleziono produktów: <?= count($products) ?></span>
</div>
<?php $showCategoryBadge = false; ?>
<?php require BASE_PATH . '/views/partials/product_grid.php'; ?>
