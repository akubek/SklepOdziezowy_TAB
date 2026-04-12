<div class="text-center mb-5">
    <h1 class="display-4 fw-bold"><?= htmlspecialchars($currentCategory['name']) ?></h1>
    <p class="lead text-muted">Wybierz interesującą Cię kategorię produktów</p>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <?php foreach ($subcategories as $sub): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 position-relative category-card">
                <img src="https://placehold.co/600x400/eeeeee/999999?text=<?= urlencode($sub['name']) ?>" 
                     class="card-img-top object-fit-cover" 
                     alt="<?= htmlspecialchars($sub['name']) ?>" 
                     style="height: 200px;">
                
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h3 class="card-title h5 fw-bold mb-0"><?= htmlspecialchars($sub['name']) ?></h3>
                    <a href="index.php?page=category&id=<?= $sub['id'] ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<hr class="my-5 text-muted">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 fw-bold m-0">Wszystkie nowości: <?= htmlspecialchars($currentCategory['name']) ?></h2>
    <span class="text-muted">Ilość produktów: <?= count($products) ?></span>
</div>

<?php require 'partials/product_grid.php'; ?>

<div class="text-center mb-5">
    <a href="index.php?page=home" class="btn btn-outline-secondary">
        &laquo; Powrót do strony głównej
    </a>
</div>
