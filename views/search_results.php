<section class="search-header mb-5">
    <h1>Wyniki wyszukiwania dla frazy: "<?= e($searchQuery) ?>"</h1>
    <p class="text-muted">Znaleziono <?= count($products) ?> produktów.</p>
</section>

<?php require BASE_PATH . '/views/partials/product_grid.php'; ?>
