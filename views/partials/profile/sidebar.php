<?php
// Oczekujemy zmiennej $active_tab, żeby podświetlić odpowiednią zakładkę
$active_tab = $active_tab ?? 'dashboard';
?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="list-group list-group-flush rounded-3">
            <a href="index.php?page=profile" class="list-group-item list-group-item-action py-3 <?= $active_tab === 'dashboard' ? 'active bg-primary text-white border-primary' : '' ?>">
                <i class="bi bi-person-badge me-2"></i> Przegląd konta
            </a>
            <a href="index.php?page=profile_orders" class="list-group-item list-group-item-action py-3 <?= $active_tab === 'orders' ? 'active bg-primary text-white border-primary' : '' ?>">
                <i class="bi bi-box-seam me-2"></i> Moje zamówienia
            </a>
            <a href="index.php?page=profile_reviews" class="list-group-item list-group-item-action py-3 <?= $active_tab === 'reviews' ? 'active bg-primary text-white border-primary' : '' ?>">
                <i class="bi bi-star me-2"></i> Moje opinie
            </a>
            <a href="index.php?page=profile_addresses" class="list-group-item list-group-item-action py-3 <?= $active_tab === 'addresses' ? 'active bg-primary text-white border-primary' : '' ?>">
                <i class="bi bi-geo-alt me-2"></i> Książka adresowa
            </a>
            <a href="index.php?page=profile_settings" class="list-group-item list-group-item-action py-3 <?= $active_tab === 'settings' ? 'active bg-primary text-white border-primary' : '' ?>">
                <i class="bi bi-gear me-2"></i> Ustawienia konta
            </a>
        </div>
    </div>
</div>
<div class="d-grid">
    <a href="index.php?page=logout" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right me-1"></i> Wyloguj się
    </a>
</div>
