<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php?page=home">Strona Główna</a></li>
        
        <?php if (!empty($categoryPath)): ?>
            <?php foreach ($categoryPath as $index => $pathStep): ?>
                <?php if ($index === count($categoryPath) - 1): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= htmlspecialchars($pathStep['name']) ?>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="index.php?page=category&id=<?= $pathStep['id'] ?>">
                            <?= htmlspecialchars($pathStep['name']) ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ol>
</nav>
