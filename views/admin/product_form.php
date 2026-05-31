<div class="container my-5">
    <div class="mb-4">
        <a href="index.php?page=admin_inventory" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left"></i> Powrót do magazynu
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h2 class="h4 fw-bold mb-0">
                <?= isset($product) ? 'Edytuj produkt: ' . htmlspecialchars($product['name']) : 'Dodaj nowy produkt' ?>
            </h2>
        </div>
        <div class="card-body p-4">
            <form action="index.php?page=admin_product_save" method="POST">
                
                <?php if (isset($product)): ?>
                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                <?php endif; ?>

                <div class="row g-3 mb-3">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Nazwa Produktu <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required 
                               value="<?= htmlspecialchars($product['name'] ?? '') ?>" 
                               placeholder="np. Koszula męska w kratę">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Cena Bazowa (PLN) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="base_price" class="form-control" required 
                               value="<?= htmlspecialchars($product['base_price'] ?? '') ?>" 
                               placeholder="199.99">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Kategoria <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Wybierz kategorię --</option>
                            <?php foreach ($allCategories as $c): ?>
                                <?php $selected = (isset($product) && $product['category_id'] == $c['id']) ? 'selected' : ''; ?>
                                <option value="<?= $c['id'] ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($c['name']) ?> 
                                    <?= $c['parent_name'] ? " (Podkategoria: {$c['parent_name']})" : " (Kategoria Główna)" ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Marka / Producent</label>
                        <input type="text" name="brand_name" class="form-control" 
                               value="<?= htmlspecialchars($product['brand_name'] ?? '') ?>" 
                               placeholder="np. Nike, Samsung">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Zdjęcie główne URL (Opcjonalne)</label>
                    <input type="text" name="main_image" class="form-control" 
                           value="<?= htmlspecialchars($product['main_image'] ?? '') ?>" 
                           placeholder="/images/products/koszula.jpg">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Opis Produktu</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Podaj szczegółowy opis produktu..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2">
                        <i class="bi bi-save me-1"></i> Zapisz Produkt
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
