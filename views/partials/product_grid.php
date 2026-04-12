<?php if (empty($products)): ?>
    <div class="alert alert-light text-center border-0 text-muted py-5">
        Brak produktów w tym dziale. Wróć tu wkrótce!
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
                        
                        <span class="badge bg-secondary mb-3 align-self-start">
                            Dział: <?= htmlspecialchars($product['category_name']) ?>
                        </span>
                        
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-primary"><?= number_format($product['base_price'], 2, ',', ' ') ?> zł</span>
                            <button class="btn btn-sm btn-outline-primary position-relative z-3">Do koszyka</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
