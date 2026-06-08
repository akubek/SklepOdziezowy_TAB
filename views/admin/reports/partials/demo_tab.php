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
                            <label class="form-check-label" for="age_1">
                                < 18</label>
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
