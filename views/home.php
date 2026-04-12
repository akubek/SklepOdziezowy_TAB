<section class="py-5 text-center container-fluid bg-light rounded-3 shadow-sm mb-5 mt-2">
    <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light mb-3">Witaj w <span class="fw-bold text-primary">Sklepie</span>!</h1>
            <p class="lead text-muted mb-4">
                Odkryj najnowszą kolekcję odzieży. Znajdziesz u nas styl, jakość i komfort na każdą okazję. 
                Przygotuj się na nowy sezon!
            </p>
            <p>
                <a href="index.php?page=catalog" class="btn btn-primary btn-lg px-4 me-md-2">Zobacz nowości</a>
                <a href="#kategorie" class="btn btn-outline-secondary btn-lg px-4">Przeglądaj działy</a>
            </p>
        </div>
    </div>
</section>

<h2 id="kategorie" class="mb-4 text-center fw-bold">Wybierz swój styl</h2>

<div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <?php if (!empty($mainCategories)): ?>
        <?php foreach ($mainCategories as $category): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 position-relative category-card">
                    <img src="https://placehold.co/600x400/eeeeee/999999?text=<?= urlencode($category['name']) ?>" class="card-img-top object-fit-cover" alt="<?= htmlspecialchars($category['name']) ?>" style="height: 250px;">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <h3 class="card-title fw-bold mb-0"><?= htmlspecialchars($category['name']) ?></h3>
                        <a href="index.php?page=category&id=<?= $category['id'] ?>" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 position-relative">
                <img src="https://placehold.co/600x400/eeeeee/999999?text=Odziez+Meska" class="card-img-top object-fit-cover" alt="Męska" style="height: 250px;">
                <div class="card-body text-center">
                    <h3 class="card-title fw-bold mb-0">Odzież Męska</h3>
                    <a href="#" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 position-relative">
                <img src="https://placehold.co/600x400/eeeeee/999999?text=Odziez+Damska" class="card-img-top object-fit-cover" alt="Damska" style="height: 250px;">
                <div class="card-body text-center">
                    <h3 class="card-title fw-bold mb-0">Odzież Damska</h3>
                    <a href="#" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 position-relative">
                <img src="https://placehold.co/600x400/eeeeee/999999?text=Odziez+Dziecieca" class="card-img-top object-fit-cover" alt="Dziecięca" style="height: 250px;">
                <div class="card-body text-center">
                    <h3 class="card-title fw-bold mb-0">Odzież Dziecięca</h3>
                    <a href="#" class="stretched-link"></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<h2 class="mb-4 text-center fw-bold">Ostatnio dodane perełki</h2>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 mb-5">
    <?php for ($i = 1; $i <= 4; $i++): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0">
                <img src="https://placehold.co/400x500/f8f9fa/343a40?text=Produkt+<?= $i ?>" class="card-img-top object-fit-cover" alt="Produkt" style="height: 300px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate">Koszulka Super Premium Model <?= $i ?></h5>
                    <p class="card-text text-muted small mb-3">Krótki opis lub kategoria</p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold text-primary">129,99 zł</span>
                        <button class="btn btn-sm btn-outline-primary add-to-cart">
                            Do koszyka
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endfor; ?>
</div>
