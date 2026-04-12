<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 fw-bold m-0"><?= htmlspecialchars($currentCategory['name']) ?></h1>
    <span class="text-muted">Znaleziono produktów: <?= count($products) ?></span>
</div>

<?php require 'partials/product_grid.php'; ?>
