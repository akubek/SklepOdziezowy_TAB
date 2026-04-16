<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="text-center mb-4 fw-bold">Logowanie</h2>
                    
                    <?php if (!empty($login_error)): ?>
                        <div class="alert alert-danger" role="alert">
                            Nieprawidłowy adres e-mail lub hasło.
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=login" method="POST">
                        <div class="mb-3">
                            <label for="login_email" class="form-label">Adres e-mail</label>
                            <input type="email" class="form-control" id="login_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="login_password" class="form-label">Hasło</label>
                            <input type="password" class="form-control" id="login_password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 btn-lg mt-3">Zaloguj się</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p>Nie masz konta? <a href="index.php?page=register">Zarejestruj się</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>