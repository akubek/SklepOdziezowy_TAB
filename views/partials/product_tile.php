<div class="card h-100 shadow-sm border-0">
    <img src="https://placehold.co/400x500/f8f9fa/343a40?text=<?= urlencode($product['name']) ?>"
        class="card-img-top object-fit-cover"
        alt="<?= e($product['name']) ?>"
        style="height: 300px;">

    <div class="card-body d-flex flex-column">
        <span class="small text-muted mb-1"><?= e($product['brand_name'] ?? 'Brak marki') ?></span>
        <h5 class="card-title text-truncate mb-2">
            <a href="index.php?page=product&id=<?= $product['id'] ?>" class="text-decoration-none text-dark stretched-link">
                <?= e($product['name']) ?>
            </a>
        </h5>

        <?php
        // 1. Opcjonalne ukrywanie badge'a (domyślnie pokazujemy)
        if ($showCategoryBadge ?? true):

            // 2. Budowanie ścieżki (Męskie » Koszule)
            $catDisplay = e($product['category_name'] ?? 'Inne');
            if (!empty($product['parent_category_name'])) {
                $parentName = e($product['parent_category_name']);
                $catDisplay = $parentName . ' &raquo; ' . $catDisplay;
            }
        ?>
            <span class="badge bg-secondary mb-3 align-self-start">
                <?= $catDisplay ?>
            </span>
        <?php endif; ?>

        <div class="mt-auto d-flex justify-content-between align-items-center">
            <span class="fs-5 fw-bold text-primary"><?= number_format($product['base_price'], 2, ',', ' ') ?> zł</span>
            <button class="btn btn-sm btn-outline-primary position-relative z-3">Do koszyka</button>
        </div>
    </div>
</div>
