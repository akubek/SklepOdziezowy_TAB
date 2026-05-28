<div class="card shadow-sm border-0 p-4 sticky-top" style="top: 20px;">
    <h5 class="mb-4">Podsumowanie zamówienia</h5>

    <?php include BASE_PATH . '/views/partials/order_items_list.php'; ?>

    <hr>

    <div class="d-flex justify-content-between mb-2">
        <span class="text-muted">Suma częściowa</span>
        <span><?= number_format($totalToPay, 2, ',', ' ') ?> zł</span>
    </div>

    <!-- Tu w przyszłości JS może dorzucić koszty dostawy -->
    <div class="d-flex justify-content-between mb-3" id="shipping-cost-row">
        <span class="text-muted">Dostawa</span>
        <span class="text-success fw-bold" id="shipping-cost-value">0 zł (do wyliczenia)</span>
    </div>

    <div class="d-flex justify-content-between mb-4 fw-bold fs-5 border-top pt-3">
        <span>Do zapłaty</span>
        <span id="final-total"><?= number_format($totalToPay, 2, ',', ' ') ?> zł</span>
    </div>

    <!-- Przycisk przeniesiony z formularza do podsumowania dla lepszego UX -->
    <button type="submit" form="checkout-form" class="btn btn-primary btn-lg w-100">
        Kupuję i płacę
    </button>
</div>
