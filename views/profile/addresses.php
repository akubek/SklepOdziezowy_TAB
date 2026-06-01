<?php if ($success_message && $success_message !== '') : ?>
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
            <?php $active_tab = 'addresses';
            require BASE_PATH . '/views/partials/profile/sidebar.php'; ?>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Książka adresowa</h2>
                <button type="button" class="btn btn-primary shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="bi bi-plus-lg me-1"></i> Dodaj nowy adres
                </button>
            </div>

            <?php if (empty($addresses)): ?>
                <div class="card shadow-sm border-0 bg-light text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-journal-x display-4 text-muted mb-3"></i>
                        <h5 class="text-muted">Brak zapisanych adresów</h5>
                        <p class="text-muted small mb-0">Dodaj swój pierwszy adres, aby przyspieszyć składanie zamówień.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($addresses as $index => $address): ?>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 h-100 position-relative">
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold text-primary mb-3">
                                        <i class="bi bi-geo-alt me-2"></i><?= e($address['title'] ?? 'Adres') ?>
                                    </h5>
                                    <p class="mb-1 fw-bold"><?= e($address['first_name'] . ' ' . $address['last_name']) ?></p>
                                    <p class="mb-1 text-muted"><?= e($address['street']) ?></p>
                                    <p class="mb-2 text-muted"><?= e($address['zip_code']) ?> <?= e($address['city']) ?></p>
                                    <p class="mb-0 small"><i class="bi bi-telephone text-muted me-1"></i> <?= e($address['phone']) ?></p>

                                    <div class="position-absolute top-0 end-0 p-3">
                                        <form action="index.php?page=profile_address_delete" method="POST" onsubmit="return confirm('Na pewno chcesz usunąć ten adres?');">
                                            <input type="hidden" name="address_id" value="<?= e($address['id'] ?? $index) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold" id="addAddressModalLabel">Dodaj nowy adres</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="index.php?page=profile_address_add" method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Nazwa adresu (np. Dom, Praca) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="title" required maxlength="50" placeholder="np. Mój dom">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-muted">Imię <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" value="<?= e($user['first_name'] ?? '') ?>" required maxlength="50">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted">Nazwisko <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" value="<?= e($user['last_name'] ?? '') ?>" required maxlength="50">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Ulica i numer domu/lokalu <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="street" required maxlength="100">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small text-muted">Kod pocztowy <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="zip_code" required pattern="^[0-9]{2}-[0-9]{3}$" placeholder="00-000" maxlength="6">
                        </div>
                        <div class="col-8">
                            <label class="form-label small text-muted">Miasto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="city" required maxlength="50">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small text-muted">Numer telefonu dla kuriera <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" name="phone" value="<?= e($user['phone_number'] ?? '') ?>" required pattern="^\+?[0-9\s\-]{9,15}$" maxlength="20">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Zapisz adres</button>
                </form>
            </div>
        </div>
    </div>
</div>
