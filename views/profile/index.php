<?php
// views/profile/index.php

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
        <div class="col-md-4 col-lg-3">
            <?php
            $active_tab = 'dashboard'; // Ustawiamy aktywną zakładkę
            require BASE_PATH . '/views/partials/profile/sidebar.php';
            ?>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 24px;">
                    <?= strtoupper(substr(e($user['first_name']), 0, 1)) ?>
                </div>
                <div>
                    <h2 class="mb-0">Witaj, <?= e($user['first_name']) ?>!</h2>
                    <p class="text-muted mb-0">Miło Cię widzieć z powrotem.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-primary mb-3">Dane profilowe</h5>
                            <p class="mb-1"><strong>E-mail:</strong> <?= e($user['email']) ?></p>
                            <p class="mb-3 text-muted small">Konto utworzone: <?= date('d.m.Y', strtotime($user['created_at'])) ?></p>
                            <a href="index.php?page=profile_settings" class="btn btn-sm btn-outline-primary">Zarządzaj kontem</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-success mb-3">Ostatnie zamówienie</h5>
                            <?php if (isset($last_order)): ?>
                                <p class="mb-1">Zamówienie #<?= $last_order['id'] ?></p>
                                <p class="mb-3 text-muted small">Status: <span class="badge bg-info text-dark"><?= $last_order['status'] ?></span></p>
                                <a href="index.php?page=profile_orders" class="btn btn-sm btn-outline-success">Zobacz wszystkie</a>
                            <?php else: ?>
                                <p class="text-muted">Nie masz jeszcze żadnych zamówień.</p>
                                <a href="index.php?page=home" class="btn btn-sm btn-primary">Zacznij zakupy</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title fw-bold mb-1">Książka adresowa</h5>
                                <p class="text-muted mb-0 small">Masz zapisanych <?= count(json_decode($user['addresses'] ?? '[]', true)) ?> adresów.</p>
                            </div>
                            <a href="index.php?page=profile_addresses" class="btn btn-sm btn-outline-secondary">Edytuj adresy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
