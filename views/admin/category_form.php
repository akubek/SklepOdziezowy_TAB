<div class="container my-5">
    <div class="mb-4">
        <a href="index.php?page=admin_inventory" class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left"></i> Powrót do magazynu
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h2 class="h4 fw-bold mb-0">
                <?= isset($category) ? 'Edytuj kategorię' : 'Dodaj nową kategorię' ?>
            </h2>
        </div>
        <div class="card-body p-4">
            <form action="index.php?page=admin_category_save" method="POST">
                
                <?php if (isset($category)): ?>
                    <input type="hidden" name="id" value="<?= $category['id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nazwa Kategorii <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required 
                           value="<?= htmlspecialchars($category['name'] ?? '') ?>" 
                           placeholder="np. Laptopy, Buty zimowe">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kategoria Nadrzędna</label>
                    <select name="parent_id" class="form-select">
                        <option value="">-- Brak (Kategoria Główna) --</option>
                        <?php foreach ($allCategories as $c): ?>
                            <?php 
                            if (isset($category) && $category['id'] == $c['id']) continue; 
                            
                            $selected = (isset($category) && $category['parent_id'] == $c['id']) ? 'selected' : '';
                            ?>
                            <option value="<?= $c['id'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">Wybierz, jeśli ta kategoria jest podkategorią innej.</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Ścieżka do obrazka URL (Opcjonalne)</label>
                    <input type="text" name="image_path" class="form-control" 
                           value="<?= htmlspecialchars($category['image_path'] ?? '') ?>" 
                           placeholder="/images/cats/laptopy.jpg">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary py-2">
                        <i class="bi bi-save me-1"></i> Zapisz Kategorię
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
