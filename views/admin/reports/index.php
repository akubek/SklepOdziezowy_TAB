<div class="container my-4">
    <h2 class="mb-4"><i class="bi bi-graph-up text-primary"></i> Panel Raportów</h2>

    <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales-report" type="button" role="tab" aria-selected="true">
                Raport Sprzedaży
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="demo-tab" data-bs-toggle="tab" data-bs-target="#demographics-report" type="button" role="tab" aria-selected="false">
                Raport Demograficzny
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reportTabsContent">

        <div class="tab-pane fade show active" id="sales-report" role="tabpanel" aria-labelledby="sales-tab">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Filtry Sprzedaży</h5>
                </div>
                <div class="card-body">
                    <form id="sales-filters" class="row g-3" action="index.php" method="GET">
                        <input type="hidden" name="page" value="admin_reports">
                        <input type="hidden" name="action" value="generate_sales">
                        <div class="col-md-3">
                            <label class="form-label">Zakres od:</label>
                            <input type="date" class="form-control sales-date" name="date_from" value="<?= date('Y-m-d', strtotime('-30 days')) ?>">
                            <div class="invalid-feedback">Data początkowa musi być równa lub mniejsza od końcowej.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Zakres do:</label>
                            <input type="date" class="form-control sales-date" name="date_to" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kategorie:</label>
                            <div class="border rounded bg-white p-2" style="max-height: 250px; overflow-y: auto;">

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
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Marki:</label>
                            <div class="border rounded bg-white p-2" style="max-height: 250px; overflow-y: auto;">

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
                        </div>
                        <div class="col-12 text-end d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-outline-secondary">Wyczyść filtry</button>   
                            <button type="submit" class="btn btn-primary">Generuj Raport Sprzedaży</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="sales-summary" class="row mb-4 d-none">
                <div class="col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Całkowita sprzedaż (Przedział)</h5>
                            <h3 id="total-revenue-val">0.00 PLN</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h5 class="card-title">Sprzedane przedmioty (Przedział)</h5>
                            <h3 id="total-items-val">0 szt.</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div id="sales-chart-container" class="card shadow-sm d-none">
                <div class="card-body">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>

            <div id="sales-empty-state" class="text-center py-5 text-muted">
                <i class="bi bi-bar-chart" style="font-size: 3rem;"></i>
                <p class="mt-3">Wybierz kryteria i kliknij "Generuj Raport Sprzedaży".</p>
            </div>
        </div>

        <div class="tab-pane fade" id="demographics-report" role="tabpanel" aria-labelledby="demo-tab">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Badanie popularności wg Demografii</h5>
                </div>
                <div class="card-body">
                    <form id="demo-filters" class="row g-3" action="index.php" method="GET">
                        <input type="hidden" name="page" value="admin_reports">
                        <input type="hidden" name="action" value="generate_demo">
                        <div class="col-md-2">
                            <label class="form-label">Grupa wiekowa:</label>
                            <div class="border rounded bg-white p-2">
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="age_groups[]" value="<18" id="age_1">
                                    <label class="form-check-label" for="age_1">< 18</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="age_groups[]" value="18-24" id="age_2">
                                    <label class="form-check-label" for="age_2">18-24</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="age_groups[]" value="25-34" id="age_3">
                                    <label class="form-check-label" for="age_3">25-34</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="age_groups[]" value="35-44" id="age_4">
                                    <label class="form-check-label" for="age_4">35-44</label>
                                </div>
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="age_groups[]" value="45+" id="age_5">
                                    <label class="form-check-label" for="age_5">45+</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Płeć:</label>
                            <div class="border rounded bg-white p-2">
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="genders[]" value="MALE" id="gender_m">
                                    <label class="form-check-label" for="gender_m">Mężczyźni</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="genders[]" value="FEMALE" id="gender_f">
                                    <label class="form-check-label" for="gender_f">Kobiety</label>
                                </div>
                                <div class="form-check mb-1">
                                    <input class="form-check-input" type="checkbox" name="genders[]" value="OTHER" id="gender_o">
                                    <label class="form-check-label" for="gender_o">Inne płci</label>
                                </div>
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" name="genders[]" value="Brak danych" id="gender_none">
                                    <label class="form-check-label" for="gender_none">Nie podano</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Miasta (po przecinku):</label>
                            <input type="text" class="form-control" name="cities" placeholder="np. Warszawa, Kraków">

                            <label class="form-label mt-2">Opcjonalny przedział aktywności:</label>
                            <div class="d-flex gap-2">
                                <input type="date" class="form-control demo-date" name="active_from">
                                <input type="date" class="form-control demo-date" name="active_to">
                            </div>
                            <div class="invalid-feedback d-block" id="demo-date-error" style="display: none !important;">Data "od" nie może być większa niż "do".</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pokaż ranking dla:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="group_by_type" value="products" id="typeProd" checked>
                                <label class="form-check-label" for="typeProd">Konkretnych produktów</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="group_by_type" value="brands" id="typeBrand">
                                <label class="form-check-label" for="typeBrand">Całych marek</label>
                            </div>
                        </div>
                        <div class="col-md-2 text-end d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Pokaż ranking</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="demo-results-container" class="card shadow-sm d-none">
                <div class="card-body">
                    <canvas id="popularityChart"></canvas>
                </div>
            </div>

            <div id="demo-empty-state" class="text-center py-5 text-muted">
                <i class="bi bi-people" style="font-size: 3rem;"></i>
                <p class="mt-3">Wybierz kryteria i kliknij "Pokaż ranking", aby załadować profil klientów.</p>
            </div>

        </div>
        <div id="demo-empty-state" class="text-center py-5 text-muted">
            <i class="bi bi-people" style="font-size: 3rem;"></i>
            <p class="mt-3">Wybierz kryteria i kliknij "Generuj", aby załadować profil klientów.</p>
        </div>
    </div>

</div>
<div id="reports-data-payload" class="d-none"
     data-active-tab="<?= e($active_tab) ?>"
     data-sales="<?= e(json_encode($salesData ?? [])) ?>"
     data-demo="<?= e(json_encode($demoData ?? [])) ?>">
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/js/reports.js"></script>