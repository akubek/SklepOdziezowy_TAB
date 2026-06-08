<div class="tab-pane fade show active" id="sales-report" role="tabpanel" aria-labelledby="sales-tab">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Filtry Sprzedaży</h5>
        </div>
        <div class="card-body">
            <form id="sales-filters" class="row g-3" action="index.php" method="GET">
                <input type="hidden" name="page" value="admin_reports">
                <input type="hidden" name="action" value="generate_sales">
                <div class="col-md-4">
                    <label class="form-label">Zakres od:</label>
                    <input type="date" class="form-control sales-date mb-2" name="date_from" value="<?= date('Y-m-d', strtotime('-30 days')) ?>" id="sales_date_from">

                    <label class="form-label">Zakres do:</label>
                    <input type="date" class="form-control sales-date mb-2" name="date_to" value="<?= date('Y-m-d') ?>" id="sales_date_to">

                    <div class="invalid-feedback mb-2" id="sales-date-error">Data "od" musi być ≤ "do".</div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="all_time" value="1" id="all_time_sales">
                        <label class="form-check-label fw-bold" for="all_time_sales">
                            Cały okres (ignoruj daty)
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategorie:</label>
                    <div class="border rounded bg-white p-2" id="cat-container" style="max-height: 250px; overflow-y: auto;">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="cat_all" value="all">
                            <label class="form-check-label fw-bold" for="cat_all">Wszystkie kategorie</label>
                        </div>

                        <hr class="my-1">

                        <?php
                        // Funkcja rekurencyjna do renderowania zagnieżdżonego HTML
                        if (!function_exists('renderCategoryTree')) {
                            function renderCategoryTree($categories)
                            {
                                // Usuwamy kropki z listy i dodajemy margines po lewej
                                echo '<ul class="list-unstyled ms-3 mb-0">';

                                foreach ($categories as $cat) {
                                    $hasChildren = !empty($cat['children']);

                                    echo '<li class="my-1">';
                                    echo '<div class="d-flex align-items-center">';

                                    // Strzałka rozwijania (ikonka Bootstrapa) - klikalna
                                    if ($hasChildren) {
                                        echo '<i class="bi bi-chevron-right me-2 toggle-tree" style="cursor: pointer; font-size: 0.8rem;" data-bs-toggle="collapse" data-bs-target="#children-of-' . $cat['id'] . '"></i>';
                                    } else {
                                        // Pusty obszar dla wyrównania rzędu, gdy brak dzieci
                                        echo '<span class="me-2" style="width: 16px; display: inline-block;"></span>';
                                    }

                                    // Checkbox dla danej kategorii
                                    echo '<div class="form-check mb-0">';
                                    echo '<input class="form-check-input category-cb" type="checkbox" name="categories[]" value="' . $cat['id'] . '" id="cat_' . $cat['id'] . '" data-id="' . $cat['id'] . '" data-parent="' . ($cat['parent_id'] ?: '0') . '">';
                                    echo '<label class="form-check-label" for="cat_' . $cat['id'] . '">' . htmlspecialchars($cat['name']) . '</label>';
                                    echo '</div>';

                                    echo '</div>'; // Koniec d-flex

                                    // Kontener na dzieci (domyślnie ukryty przez klasę "collapse")
                                    if ($hasChildren) {
                                        echo '<div class="collapse" id="children-of-' . $cat['id'] . '">';
                                        renderCategoryTree($cat['children']); // Rekurencyjne wywołanie
                                        echo '</div>';
                                    }
                                    echo '</li>';
                                }
                                echo '</ul>';
                            }
                        }

                        // Wywołanie funkcji dla głównych kategorii pobranych z kontrolera
                        if (!empty($categories)) {
                            renderCategoryTree($categories);
                        }
                        ?>
                    </div>
                    <div class="invalid-feedback d-block" id="cat-error" style="display: none !important;">
                        Wybierz co najmniej 1 kategorię.
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Marki:</label>
                    <div class="border rounded bg-white p-2" id="brand-container" style="max-height: 250px; overflow-y: auto;">

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="brand_all" value="all">
                            <label class="form-check-label fw-bold" for="brand_all">Wszystkie marki</label>
                        </div>
                        <hr class="my-1">

                        <?php if (!empty($brands)): ?>
                            <?php foreach ($brands as $index => $brand): ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input brand-cb" type="checkbox" name="brands[]" value="<?= htmlspecialchars($brand) ?>" id="brand_<?= $index ?>">
                                    <label class="form-check-label" for="brand_<?= $index ?>">
                                        <?= htmlspecialchars($brand) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                    <div class="invalid-feedback d-block" id="brand-error" style="display: none !important;">
                        Wybierz co najmniej 1 markę.
                    </div>
                </div>
                <div class="col-12 text-end d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary">Wyczyść filtry</button>
                    <button type="submit" class="btn btn-primary">Generuj Raport Sprzedaży</button>
                </div>
            </form>
        </div>
    </div>

    <div id="sales-report-header" class="mb-4 d-none border-start border-primary border-4 ps-3">
        <h4 class="mb-1" id="sales-report-title">Raport sprzedaży</h4>
        <p class="text-muted mb-0 small" id="sales-report-filters">
        </p>
    </div>

    <div id="sales-summary" class="row mb-4 d-none">
        <div class="col-md-6">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title opacity-75">Całkowita wartość sprzedaży</h5>
                    <h3 id="total-revenue-val" class="mb-0 fw-bold">0.00 PLN</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title opacity-75">Sprzedane przedmioty</h5>
                    <h3 id="total-items-val" class="mb-0 fw-bold">0 szt.</h3>
                </div>
            </div>
        </div>
    </div>

    <div id="sales-chart-container" class="card shadow-sm mb-4 d-none">
        <div class="card-body">
            <canvas id="salesTrendChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <div id="sales-details-container" class="card shadow-sm d-none">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Szczegóły sprzedaży - rozbicie dzienne</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>Data</th>
                            <th>Sprzedane przedmioty</th>
                            <th>Przychód (PLN)</th>
                        </tr>
                    </thead>
                    <tbody id="sales-table-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="sales-empty-state" class="text-center py-5 text-muted">
        <i class="bi bi-bar-chart" style="font-size: 3rem;"></i>
        <p class="mt-3">Wybierz kryteria i kliknij "Generuj Raport Sprzedaży".</p>
    </div>
</div>
