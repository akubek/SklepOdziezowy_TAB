<?php
// views/partials/order_items_list.php

// Ten partial oczekuje zmiennej $items (tablicy z pozycjami zamówienia/koszyka)
// oraz opcjonalnie zmiennej $isSuccessPage (żeby wiedzieć, czy wyłączyć pewne rzeczy, np. przyciski)
$isSuccessPage = $isSuccessPage ?? false;
?>

<ul class="list-group list-group-flush mb-4">
    <?php foreach ($items as $item): ?>
        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <!-- Miniaturka z ilością jako "badge" -->
                <div class="position-relative me-3">
                    <?php
                    // Na stronie sukcesu image może nazywać się inaczej lub go nie być w bazie (OrderManager)
                    // Musisz się upewnić, że $item['image'] jest dostępne, w przeciwnym razie ustaw fallback.
                    $imageSrc = $item['image'] ?? 'https://placehold.co/50x50?text=Brak+zdjęcia';
                    ?>
                    <img src="<?= e($imageSrc) ?>" alt="<?= e($item['name'] ?? $item['variant_name'] ?? '') ?>" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                        <?= (int)$item['quantity'] ?>
                    </span>
                </div>

                <!-- Dane produktu -->
                <div>
                    <div class="fw-bold" style="font-size: 0.9rem;">
                        <?= e($item['name'] ?? $item['variant_name'] ?? 'Nieznany produkt') ?>
                    </div>

                    <?php if (!$isSuccessPage && !empty($item['attributes'])): ?>
                        <!-- Wyświetlanie atrybutów TYLKO w koszyku (na stronie sukcesu mamy już złożoną nazwę wariantu!) -->
                        <small class="text-muted" style="font-size: 0.8rem;">
                            <?php
                            // Opcjonalnie: słownik tłumaczeń dla atrybutów, żeby było "Rozmiar: L" zamiast "size: L"
                            $attrLabels = ['size' => 'Rozmiar', 'color' => 'Kolor', 'material' => 'Materiał'];
                            $attrStrings = [];
                            foreach ($item['attributes'] as $k => $v) {
                                $translatedKey = $attrLabels[$k] ?? $k;
                                $attrStrings[] = e($translatedKey) . ': ' . e($v);
                            }
                            echo implode(', ', $attrStrings);
                            ?>
                        </small>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Cena (Suma dla tej pozycji) -->
            <span class="fw-bold" style="font-size: 0.95rem;">
                <?php
                // W koszyku mamy 'subtotal', na stronie sukcesu mamy 'unit_price' * 'quantity'
                $subtotal = $item['subtotal'] ?? ($item['unit_price'] * $item['quantity']);
                ?>
                <?= number_format($subtotal, 2, ',', ' ') ?> zł
            </span>
        </li>
    <?php endforeach; ?>
</ul>
