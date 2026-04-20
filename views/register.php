<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 fw-bold">Załóż konto</h2>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error_message) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($success_message) ?>
                        </div>
                    <?php endif; ?>

                    <form id="register-form" action="index.php?page=register" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">Imię</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <div class="invalid-feedback">Proszę podać imię.</div>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Nazwisko</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="invalid-feedback">Proszę podać nazwisko.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adres e-mail</label>
                            <input type="email" style="text-transform: lowercase;" class="form-control" id="email" name="email" required autocapitalize="none">
                            <div class="invalid-feedback">Podaj poprawny format adresu e-mail (np. jan@kowalski.pl).</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Hasło</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">Hasło musi składać się z co najmniej 8 znaków.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg mt-3" id="register-btn" disabled>Zarejestruj się</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Masz już konto? <a href="index.php?page=login">Zaloguj się</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/auth_validation.js"></script>
