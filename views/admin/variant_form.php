<div class="container my-5">
    <div class="mb-4">
        <a href="index.php?page=admin_variants&product_id=<?= $product['id'] ?>" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left"></i> Powrót do wariantów
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h2 class="h4 fw-bold mb-0">
                <?= isset($variant) ? 'Edytuj wariant' : 'Dodaj nowy wariant' ?>
            </h2>
            <small class="text-muted">Dla produktu: <?= htmlspecialchars($product['name']) ?></small>
        </div>
        <div class="card-body p-4">
            <form action="index.php?page=admin_variant_save" method="POST">
                
                <?php if (isset($variant)): ?>
                    <input type="hidden" name="id" value="<?= $variant['id'] ?>">
                <?php endif; ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">SKU (Kod produktu) <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" required 
                               value="<?= htmlspecialchars($variant['sku'] ?? '') ?>" 
                               placeholder="np. KOSZ-KRA-XL-CZER">
                        <small class="text-muted">Musi być unikalny w całym sklepie!</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Cena (PLN) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="variant_price" class="form-control" required 
                               value="<?= htmlspecialchars($variant['variant_price'] ?? $product['base_price']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Stan w magazynie <span class="text-danger">*</span></label>
                        <input type="number" min="0" name="stock_quantity" class="form-control" required 
                               value="<?= htmlspecialchars($variant['stock_quantity'] ?? 0) ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Atrybuty (JSON) <span class="text-danger">*</span></label>
                    <textarea name="attributes" class="form-control font-monospace" rows="3" required><?= htmlspecialchars($variant['attributes'] ?? '{"rozmiar": "XL", "kolor": "Czerwony"}') ?></textarea>
                    <small class="text-muted">Pamiętaj o podwójnych cudzysłowach w składni JSON.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Zdjęcia (JSON Array) <span class="text-danger">*</span></label>
                    <textarea name="images" class="form-control font-monospace" rows="2" required><?= htmlspecialchars($variant['images'] ?? '["/images/czerwona_1.jpg"]') ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2">
                        <i class="bi bi-save me-1"></i> Zapisz Wariant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
