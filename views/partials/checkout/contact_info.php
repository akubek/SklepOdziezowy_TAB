<!-- views/partials/checkout/_contact_info.php -->

<!-- 1. E-MAIL -->
<div class="col-md-12">
    <label for="shipping_email" class="form-label">Adres e-mail <span class="text-danger">*</span></label>
    <?php if ($currentUser): ?>
        <input type="email" class="form-control" id="shipping_email" value="<?= e($currentUser['email']) ?>" disabled>
        <div class="form-text">Potwierdzenie wyślemy na adres przypisany do konta.</div>
    <?php else: ?>
        <input type="email" class="form-control" id="shipping_email" name="shipping[email]" placeholder="np. jan@kowalski.pl" maxlength="255" required>
        <div class="invalid-feedback">Podaj poprawny adres e-mail (np. jan@kowalski.pl).</div>
    <?php endif; ?>
</div>

<!-- 2. IMIĘ -->
<div class="col-md-6">
    <label for="shipping_first_name" class="form-label">Imię <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="shipping_first_name" name="shipping[first_name]"
        value="<?= $currentUser ? e($currentUser['first_name']) : '' ?>" maxlength="50" required>
    <div class="invalid-feedback">Proszę podać swoje imię.</div>
</div>

<!-- 3. NAZWISKO -->
<div class="col-md-6">
    <label for="shipping_last_name" class="form-label">Nazwisko <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="shipping_last_name" name="shipping[last_name]"
        value="<?= $currentUser ? e($currentUser['last_name']) : '' ?>" maxlength="50" required>
    <div class="invalid-feedback">Proszę podać swoje nazwisko.</div>
</div>

<!-- 4. TELEFON -->
<div class="col-md-12">
    <label for="shipping_phone" class="form-label">Numer telefonu <span class="text-danger">*</span></label>
    <input type="tel" class="form-control" id="shipping_phone" name="shipping[phone]"
        value="<?= $currentUser ? e($currentUser['phone_number']) : '' ?>"
        pattern="^([0-9]{3}[\s\-]?){2}[0-9]{3}$" required>
    <div class="invalid-feedback">Podaj poprawny, 9-cyfrowy numer telefonu (np. 123 456 789).</div>
</div>
