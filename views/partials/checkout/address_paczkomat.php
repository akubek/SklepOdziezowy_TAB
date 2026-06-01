<h5 class="mb-3 mt-4 border-top pt-3">Dane do Paczkomatu</h5>
<div class="row g-3">
    <div class="col-md-12 border-top pt-3 mt-4">
        <label for="paczkomat_code" class="form-label">Kod Paczkomatu <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="paczkomat_code" name="shipping[paczkomat_code]" placeholder="np. WAW123M"
            pattern="^[a-zA-Z]{3}[0-9a-zA-Z]{3,4}$" maxlength="7" required>
        <div class="invalid-feedback">Podaj poprawny kod paczkomatu.</div>
    </div>
</div>
