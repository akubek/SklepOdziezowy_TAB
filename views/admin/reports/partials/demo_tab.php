<div class="tab-pane fade" id="demographics-report" role="tabpanel" aria-labelledby="demo-tab">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Badanie popularności wg Demografii</h5>
        </div>
        <div class="card-body">
            <form id="demo-filters" class="row g-3" action="index.php" method="GET">
                <input type="hidden" name="page" value="admin_reports">
                <input type="hidden" name="action" value="generate_demo">

                <div class="col-md-3">
                    <label class="form-label">Aktywność konta od:</label>
                    <input type="date" class="form-control demo-date mb-2" name="active_from" value="<?= date('Y-m-d', strtotime('-1 year')) ?>">

                    <label class="form-label">Aktywność konta do:</label>
                    <input type="date" class="form-control demo-date mb-2" name="active_to" value="<?= date('Y-m-d') ?>">

                    <div class="invalid-feedback mb-2" id="demo-date-error">Data "od" musi być ≤ "do".</div>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="all_time" value="1" id="all_time_demo">
                        <label class="form-check-label fw-bold" for="all_time_demo">
                            Cały okres (ignoruj daty)
                        </label>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Grupa wiekowa:</label>
                    <div class="border rounded bg-white p-2" id="age-container">
                        <div class="form-check mb-1">
                            <input class="form-check-input age-cb" type="checkbox" name="age_groups[]" value="<18" id="age_1" checked>
                            <label class="form-check-label" for="age_1">
                                < 18</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input age-cb" type="checkbox" name="age_groups[]" value="18-24" id="age_2" checked>
                            <label class="form-check-label" for="age_2">18-24</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input age-cb" type="checkbox" name="age_groups[]" value="25-34" id="age_3" checked>
                            <label class="form-check-label" for="age_3">25-34</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input age-cb" type="checkbox" name="age_groups[]" value="35-44" id="age_4" checked>
                            <label class="form-check-label" for="age_4">35-44</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input age-cb" type="checkbox" name="age_groups[]" value="45+" id="age_5" checked>
                            <label class="form-check-label" for="age_5">45+</label>
                        </div>
                    </div>
                    <div class="invalid-feedback d-block" id="age-error" style="display: none !important;">
                        Wybierz co najmniej 1 grupę.
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Płeć:</label>
                    <div class="border rounded bg-white p-2" id="gender-container">
                        <div class="form-check mb-1">
                            <input class="form-check-input gender-cb" type="checkbox" name="genders[]" value="MALE" id="gender_m" checked>
                            <label class="form-check-label" for="gender_m">Mężczyźni</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input gender-cb" type="checkbox" name="genders[]" value="FEMALE" id="gender_f" checked>
                            <label class="form-check-label" for="gender_f">Kobiety</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input gender-cb" type="checkbox" name="genders[]" value="OTHER" id="gender_o" checked>
                            <label class="form-check-label" for="gender_o">Inne płci</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input gender-cb" type="checkbox" name="genders[]" value="Brak danych" id="gender_none" checked>
                            <label class="form-check-label" for="gender_none">Nie podano</label>
                        </div>
                    </div>
                    <div class="invalid-feedback d-block" id="gender-error" style="display: none !important;">
                        Wybierz co najmniej 1 płeć.
                    </div>
                </div>

                <div class="col-md-3 d-flex flex-column justify-content-between">
                    <div>
                        <label class="form-label">Miasta (po przecinku):</label>
                        <input type="text" class="form-control mb-3" name="cities" placeholder="np. Warszawa, Kraków">

                        <label class="form-label">Pokaż ranking dla:</label>
                        <div class="border rounded bg-white p-2 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="group_by_type" value="products" id="typeProd" checked>
                                <label class="form-check-label" for="typeProd">Produktów</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="group_by_type" value="brands" id="typeBrand">
                                <label class="form-check-label" for="typeBrand">Marek</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="group_by_type" value="categories" id="typeCat">
                                <label class="form-check-label" for="typeCat">Kategorii</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-outline-secondary w-100">Wyczyść</button>
                        <button type="submit" class="btn btn-primary w-100">Pokaż ranking</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="demo-report-header" class="mb-4 d-none border-start border-info border-4 ps-3">
        <h4 class="mb-1" id="demo-report-title">Raport Demograficzny</h4>
        <p class="text-muted mb-0 small" id="demo-report-filters"></p>
    </div>
    <!--
    <div id="demo-chart-container" class="card shadow-sm mb-4 d-none">
        <div class="card-body">
            <canvas id="popularityChart" style="max-height: 400px;"></canvas>
        </div>
    </div>
    -->

    <div id="demo-details-container" class="card shadow-sm d-none">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Szczegóły Rankingu</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="table-dark sticky-top">
                        <tr>
                            <th>Miejsce</th>
                            <th id="demo-table-name-header">Nazwa</th>
                            <th>Ilość kupionych sztuk</th>
                        </tr>
                    </thead>
                    <tbody id="demo-table-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="demo-empty-state" class="text-center py-5 text-muted">
        <i class="bi bi-people" style="font-size: 3rem;"></i>
        <p class="mt-3">Wybierz kryteria i kliknij "Pokaż ranking", aby załadować profil klientów.</p>
    </div>
</div>
