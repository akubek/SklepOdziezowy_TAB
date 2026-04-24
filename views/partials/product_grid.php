<?php if (empty($products)): ?>
    <div class="alert alert-light text-center border-0 text-muted py-5">
        Brak produktów do wyświetlenia. Wróć tu wkrótce!
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        <?php foreach ($products as $product): ?>
            <div class="col">
                <?php require BASE_PATH . '/views/partials/product_tile.php'; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
