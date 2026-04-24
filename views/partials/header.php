<?php
$categoryManager = $container['categoryManager']($container);
$mainCategories = $categoryManager->getSubCategories(1);
?>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?page=home">
                <span class="text-primary">sklep</span>odzieżowy
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarnav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarnav">
                <ul class="navbar-nav mx-auto">
                    <?php if (!empty($mainCategories)): ?>
                        <?php foreach ($mainCategories as $category): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=category&id=<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <div class="d-flex align-items-center gap-3">

                    <form class="d-flex" action="index.php" method="GET">
                        <input type="hidden" name="page" value="search">
                        <input class="form-control me-2" type="search" name="q" placeholder="Czego szukasz?" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Szukaj</button>
                    </form>

                    <div class="d-flex gap-2">
                        <a href="index.php?page=cart" class="btn btn-outline-light text-nowrap">
                            Koszyk <span class="badge bg-primary">0</span>
                        </a>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="index.php?page=profile" class="btn btn-outline-light text-nowrap">Moje konto</a>
                            <a href="index.php?page=logout" class="btn btn-danger text-nowrap">Wyloguj</a>
                        <?php else: ?>
                            <a href="index.php?page=login" class="btn btn-primary text-nowrap">Zaloguj</a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
