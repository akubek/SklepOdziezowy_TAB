<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?page=home">Strona Główna</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($currentCategory['name']) ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h2 fw-bold m-0"><?= htmlspecialchars($currentCategory['name']) ?></h1>
    <span class="text-muted">Znaleziono produktów: <?= count($products) ?></span>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info text-center py-5">
        <h4 class="alert-heading">Brak produktów</h4>
        <p>W tej kategorii nie ma jeszcze żadnych produktów. Wróć później!</p>
        <a href="index.php?page=home" class="btn btn-outline-primary mt-3">Wróć na stronę główną</a>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <img src="https://placehold.co/400x500/f8f9fa/343a40?text=<?= urlencode($product['name']) ?>" 
                         class="card-img-top object-fit-cover" 
                         alt="<?= htmlspecialchars($product['name']) ?>" 
                         style="height: 300px;">
                    
                    <div class="card-body d-flex flex-column">
                        <span class="small text-muted mb-1"><?= htmlspecialchars($product['brand_name'] ?? 'Brak marki') ?></span>
                        
                        <h5 class="card-title text-truncate mb-2">
                            <a href="#!" class="text-decoration-none text-dark stretched-link">
                                <?= htmlspecialchars($product['name']) ?>
                            </a>
                        </h5>
                        
                        <p class="card-text text-muted small mb-3 text-truncate">
                            <?= htmlspecialchars($product['description'] ?? '') ?>
                        </p>
                        
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-primary"><?= number_format($product['base_price'], 2, ',', ' ') ?> zł</span>
                            <button class="btn btn-sm btn-outline-primary position-relative z-3">
                                Do koszyka
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
