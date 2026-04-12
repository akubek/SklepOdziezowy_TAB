<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?page=home">Strona Główna</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($currentCategory['name']) ?></li>
    </ol>
</nav>

<div class="text-center mb-5">
    <h1 class="display-4 fw-bold"><?= htmlspecialchars($currentCategory['name']) ?></h1>
    <p class="lead text-muted">Wybierz interesującą Cię kategorię produktów</p>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <?php foreach ($subcategories as $sub): ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 position-relative category-card">
                <img src="https://placehold.co/600x400/eeeeee/999999?text=<?= urlencode($sub['name']) ?>" 
                     class="card-img-top object-fit-cover" 
                     alt="<?= htmlspecialchars($sub['name']) ?>" 
                     style="height: 200px;">
                
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <h3 class="card-title h5 fw-bold mb-0"><?= htmlspecialchars($sub['name']) ?></h3>
                    <a href="index.php?page=category&id=<?= $sub['id'] ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="text-center mb-5">
    <a href="index.php?page=home" class="btn btn-outline-secondary">
        &laquo; Powrót do strony głównej
    </a>
</div>
