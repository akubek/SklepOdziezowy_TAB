<?php
// views/partials/profile/address_list.php

// Upewniamy się, że mamy z czym pracować
$addresses = $addresses ?? [];
?>

<?php if (empty($addresses)): ?>
    <div class="text-center text-muted py-4">
        <i class="bi bi-house text-secondary" style="font-size: 2rem;"></i>
        <p class="mt-2 mb-0">Nie masz jeszcze żadnych zapisanych adresów.</p>
        <small>Dodanie adresu przyspieszy proces składania zamówienia.</small>
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 g-3">
        <?php foreach ($addresses as $index => $address): ?>
            <div class="col">
                <div class="card h-100 border rounded-3 position-relative bg-light">
                    <div class="card-body">
                        <h6 class="card-title fw-bold">
                            <i class="bi bi-geo-alt-fill text-primary me-1"></i>
                            <?= e($address['label'] ?? 'Adres dostawy') ?>
                        </h6>

                        <p class="card-text small mb-3">
                            <?= e($address['first_name'] ?? '') ?> <?= e($address['last_name'] ?? '') ?><br>
                            <?= e($address['street'] ?? '') ?><br>
                            <?= e($address['zip_code'] ?? '') ?> <?= e($address['city'] ?? '') ?><br>
                            Tel: <?= e($address['phone'] ?? 'Brak') ?>
                        </p>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="#" class="btn btn-sm btn-outline-secondary">Edytuj</a>

                            <form action="index.php?page=delete_address" method="POST" class="d-inline" onsubmit="return confirm('Czy na pewno chcesz usunąć ten adres?');">
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
