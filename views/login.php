<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 fw-bold">Logowanie</h2>

                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success" role="alert"><?= e($success_message) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($login_error)): ?>
                        <div class="alert alert-danger" role="alert"><?= e($login_error) ?></div>
                    <?php endif; ?>

                    <form id="login-form" action="index.php?page=login" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adres e-mail</label>
                            <input type="email" style="text-transform: lowercase;" class="form-control" id="email" name="email" required autocapitalize="none">
                            <div class="invalid-feedback">Wpisz poprawny format e-mail.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Hasło</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">Hasło nie może być puste.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg mt-3" >Zaloguj się</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Nie masz konta? <a href="index.php?page=register">Zarejestruj się</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/js/auth_validation.js"></script>
