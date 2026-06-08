<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sklep Odzieżowy MVC' ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        // Jeśli w pamięci jest zapisana pozycja scrolla, ukryj stronę przed jej narysowaniem
        if (sessionStorage.getItem('reportScrollPos')) {
            //document.documentElement.style.visibility = 'hidden';
        }
    </script>
</head>


<body class="bg-light">

    <?php require BASE_PATH . '/views/partials/header.php'; ?>

    <main class="container mt-4">
        <?= $content ?>
    </main>

    <?php require BASE_PATH . '/views/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>

    <?php if ($viewName === 'product_details'): ?>
        <script src="js/product.js"></script>
    <?php endif; ?>

    <?php if ($viewName === 'cart'): ?>
        <script src="js/cart.js"></script>
    <?php endif; ?>

    <?php if (isset($viewName) && strpos($viewName, 'profile') === 0): ?>
        <script src="js/profile.js"></script>
    <?php endif; ?>

</body>

</html>
