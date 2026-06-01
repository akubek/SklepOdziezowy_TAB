<h5 class="mb-3 mt-4 border-top pt-3">Adres dostawy</h5>
<div class="row g-3">
    <!-- Pola specyficzne dla kuriera -->
    <div class="col-12 mt-4 border-top pt-3">
        <label for="street" class="form-label">Ulica i numer domu/mieszkania <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="street" name="shipping[street]" maxlength="100" required>
        <div class="invalid-feedback">Podaj ulicę i numer mieszkania.</div>
    </div>

    <div class="col-md-4">
        <label for="zip_code" class="form-label">Kod pocztowy <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="zip_code" name="shipping[zip_code]" placeholder="np. 00-000" pattern="^[0-9]{2}-[0-9]{3}$" required>
        <div class="invalid-feedback">Podaj kod pocztowy w odpowiednim formacie XX-XXX.</div>
    </div>

    <div class="col-md-8">
        <label for="city" class="form-label">Miejscowość <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="city" name="shipping[city]" maxlength="50" required>
        <div class="invalid-feedback">Podaj miejscowość.</div>
    </div>
</div>
