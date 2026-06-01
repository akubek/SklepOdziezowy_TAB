<?php
// views/profile/settings

// Komunikaty o sukcesie lub błędzie (zmienione na czytelniejsze dla odczytu)
if ($success_message && $success_message !== '') : ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <?= e($success_message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error_message && $error_message !== ''): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <?= e($error_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-4 col-lg-3 mb-4">
            <?php $active_tab = 'settings';
            require BASE_PATH . '/views/partials/profile/sidebar.php'; ?>
        </div>

        <div class="col-md-8 col-lg-9">
            <h2 class="mb-4">Ustawienia konta</h2>

            <?php if (isset($_SESSION['profile_success'])): ?>
                <div class="alert alert-success shadow-sm mb-4">
                    <i class="bi bi-check-circle me-2"></i> <?= e($_SESSION['profile_success']);
                                                            unset($_SESSION['profile_success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['profile_error'])): ?>
                <div class="alert alert-danger shadow-sm mb-4">
                    <i class="bi bi-exclamation-triangle me-2"></i> <?= e($_SESSION['profile_error']);
                                                                    unset($_SESSION['profile_error']); ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Dane osobowe</h5>
                    <button type="button" id="editProfileBtn" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edytuj dane
                    </button>
                </div>
                <div class="card-body p-4">
                    <form id="profileForm" method="POST" action="index.php?page=profile_update">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Imię <span class="text-danger">*</span></label>
                                <input type="text" class="form-control profile-input" name="first_name" value="<?= e($user['first_name']) ?>" maxlength="50" required disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Nazwisko <span class="text-danger">*</span></label>
                                <input type="text" class="form-control profile-input" name="last_name" value="<?= e($user['last_name']) ?>" maxlength="50" required disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Adres e-mail <span class="text-danger">*</span></label>
                                <input type="email" class="form-control profile-input" name="email" value="<?= e($user['email']) ?>" maxlength="255" required disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Numer telefonu</label>
                                <input type="text" class="form-control profile-input" name="phone_number" value="<?= e($user['phone_number'] ?? '') ?>"
                                    pattern="^\+?[0-9\s\-]{9,15}$" title="Podaj poprawny numer telefonu (np. 123456789 lub +48 123 456 789)" maxlength="20" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Data urodzenia</label>
                                <input type="date" class="form-control profile-input" name="birth_date" value="<?= e($user['birth_date'] ?? '') ?>"
                                    max="<?= date('Y-m-d') ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small mb-1">Płeć</label>
                                <select class="form-select profile-input" name="gender" disabled>
                                    <option value="">Wybierz...</option>
                                    <option value="MALE" <?= ($user['gender'] ?? '') === 'MALE' ? 'selected' : '' ?>>Mężczyzna</option>
                                    <option value="FEMALE" <?= ($user['gender'] ?? '') === 'FEMALE' ? 'selected' : '' ?>>Kobieta</option>
                                    <option value="OTHER" <?= ($user['gender'] ?? '') === 'OTHER' ? 'selected' : '' ?>>Inna</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4" id="profileActions" style="display: none;">
                            <button type="submit" class="btn btn-primary px-4 fw-bold me-2">Zapisz zmiany</button>
                            <button type="button" id="cancelEditBtn" class="btn btn-light">Anuluj</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 border-top border-warning border-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Zmiana hasła</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="index.php?page=password_change">
                        <div class="mb-3">
                            <label class="form-label">Obecne hasło</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Nowe hasło</label>
                                <input type="password" class="form-control" name="new_password" minlength="8" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Powtórz nowe hasło</label>
                                <input type="password" class="form-control" name="confirm_password" minlength="8" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning fw-bold">Zmień hasło</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
