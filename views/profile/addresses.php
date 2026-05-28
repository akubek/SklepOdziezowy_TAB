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
            <?php
            $active_tab = 'addresses';
            require BASE_PATH . '/views/partials/profile/sidebar.php';
            ?>
        </div>

        <div class="col-md-8 col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Książka adresowa</h2>
                <a href="index.php?page=profile_address_add" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Dodaj adres
                </a>
            </div>

            <?php if (empty($addresses)): ?>
                <div class="card shadow-sm border-0 text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-geo-alt text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Brak zapisanych adresów</h5>
                        <p class="text-muted">Dodaj adres, aby przyspieszyć proces składania zamówienia w przyszłości.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($addresses as $index => $address): ?>
                        <div class="col">
                            <div class="card shadow-sm border-0 h-100 position-relative">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">
                                        <i class="bi bi-house-door text-primary me-2"></i>
                                        <?= htmlspecialchars($address['label'] ?? 'Adres domyślny') ?>
                                    </h5>

                                    <p class="card-text mb-1"><strong><?= htmlspecialchars($address['first_name'] . ' ' . $address['last_name']) ?></strong></p>
                                    <p class="card-text mb-1 text-muted"><?= htmlspecialchars($address['street']) ?></p>
                                    <p class="card-text mb-3 text-muted"><?= htmlspecialchars($address['zip_code'] . ' ' . $address['city']) ?></p>
                                    <p class="card-text small"><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($address['phone'] ?? 'Brak') ?></p>

                                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                                        <a href="index.php?page=profile_address_edit&id=<?= $index ?>" class="btn btn-sm btn-outline-secondary">Edytuj</a>

                                        <form action="index.php?page=profile_address_delete" method="POST" class="m-0" onsubmit="return confirm('Na pewno usunąć ten adres?');">
                                            <input type="hidden" name="address_index" value="<?= $index ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
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
