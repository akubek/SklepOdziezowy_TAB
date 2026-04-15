<div class="row" id="product-container" data-variants='<?= json_encode($product['variants']) ?>'>
    <div class="col-md-6">
        <img id="main-product-image" src="https://placehold.co/600x800" class="img-fluid" alt="">
    </div>
    <div class="col-md-6">
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <h2 id="current-price" class="text-primary"><?= number_format($product['base_price'], 2) ?> zł</h2>
        
        <div class="mt-4">
            <h5>Wybierz wariant:</h5>
            <select id="variant-selector" class="form-select">
                <?php foreach ($product['variants'] as $variant): 
                    $attrs = json_decode($variant['attributes'], true); ?>
                    <option value="<?= $variant['id'] ?>">
                        <?= $attrs['size'] ?? '' ?> <?= $attrs['color'] ?? '' ?> (SKU: <?= $variant['sku'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mt-4">
            <p id="stock-info">Dostępność: Wybierz wariant</p>
            <button id="add-to-cart-btn" class="btn btn-primary btn-lg">Dodaj do koszyka</button>
        </div>
    </div>
</div>
