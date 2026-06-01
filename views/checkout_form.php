<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 fw-bold">Dane Dostawy</h2>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success text-center py-4">
                            <h4 class="alert-heading">Dziękujemy za zamówienie!</h4>
                            <p>Twoje zamówienie zostało przyjęte do realizacji.</p>
                            <hr>
                            <a href="index.php?page=home" class="btn btn-primary">Wróć do sklepu</a>
                        </div>
                    <?php else: ?>
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form id="checkout-form" action="index.php?page=checkout_form" method="POST" novalidate>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label for="first_name" class="form-label">Imię</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required maxlength="32" value="<?= htmlspecialchars($formData['first_name'] ?? '') ?>">
                                    <div class="invalid-feedback">Imię jest wymagane (max 32 znaki).</div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="last_name" class="form-label">Nazwisko</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required maxlength="32" value="<?= htmlspecialchars($formData['last_name'] ?? '') ?>">
                                    <div class="invalid-feedback">Nazwisko jest wymagane (max 32 znaki).</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Adres e-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
                                <div class="invalid-feedback">Podaj poprawny adres e-mail.</div>
                            </div>

                            <div class="mb-3">
                                <label for="street" class="form-label">Ulica i numer domu</label>
                                <input type="text" class="form-control" id="street" name="street" required maxlength="64" value="<?= htmlspecialchars($formData['street'] ?? '') ?>">
                                <div class="invalid-feedback">Podaj adres (max 64 znaki).</div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-8">
                                    <label for="city" class="form-label">Miasto</label>
                                    <input type="text" class="form-control" id="city" name="city" required maxlength="32" value="<?= htmlspecialchars($formData['city'] ?? '') ?>">
                                    <div class="invalid-feedback">Podaj miasto (max 32 znaki).</div>
                                </div>
                                <div class="col-sm-4">
                                    <label for="zip_code" class="form-label">Kod pocztowy</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="00-000" required value="<?= htmlspecialchars($formData['zip_code'] ?? '') ?>">
                                    <div class="invalid-feedback">Format: 00-000.</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="paczkomat" class="form-label">Kod Paczkomatu</label>
                                <input type="text" class="form-control" id="paczkomat" name="paczkomat" placeholder="np. WAW01A" required maxlength="10" value="<?= htmlspecialchars($formData['paczkomat'] ?? '') ?>">
                                <div class="invalid-feedback">Podaj poprawny kod Paczkomatu (np. AAA01A).</div>
                                <div class="form-text">Kod składa się z 3 liter, cyfr i opcjonalnie litery na końcu.</div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-3">
                                <a href="index.php?page=cart" class="btn btn-outline-secondary flex-grow-1">Wróć do koszyka</a>
                                <button type="submit" class="btn btn-primary flex-grow-1 btn-lg" id="checkout-btn">Złóż zamówienie</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/checkout_validation.js"></script>
