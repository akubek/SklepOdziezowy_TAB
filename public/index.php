<?php
// public/index.php
require_once __DIR__ . '/../bootstrap/init.php';

try {
    //prepare structures, container with data that is injected, and routes to map pages to actions
    $container = require_once BASE_PATH . '/bootstrap/dependencies.php';
    $routes = require_once BASE_PATH . '/config/routes.php';
    
    $page = $_GET['page'] ?? 'home';

    // check if page exitsts
    if (!array_key_exists($page, $routes)) {
        $page = '404';
        http_response_code(404); //todo move to Error controller logic
    }

    global $container; //todo move?
    require BASE_PATH . '/views/partials/header.php';
    $routes[$page]($container); //todo move to renderView, and move renderView to actual controller logic (how to display)
    require BASE_PATH . '/views/partials/footer.php';

} catch (Throwable $e) { // thorwable to catch everything
    error_log("CRITICAL ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    
    http_response_code(500);
     
    if (file_exists(BASE_PATH . '/views/errors/500.php')) {
        require BASE_PATH . '/views/errors/500.php';
    } else { // fallback to default 500 message
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <title>Awaria systemu</title>
            <style>
                body { font-family: sans-serif; line-height: 1.5; padding: 10%; text-align: center; color: #444; background: #fdfdfd; }
                h1 { color: #dc3545; }
                .box { border: 1px solid #ddd; padding: 20px; display: inline-block; background: #fff; }
            </style>
        </head>
        <body>
            <div class="box">
                <h1>Błąd krytyczny</h1>
                <p>Przepraszamy, aplikacja napotkała poważny problem techniczny.</p>
                <p><small>Kod błędu: 500 | Spróbuj odświeżyć stronę za chwilę.</small></p>
            </div>
        </body>
        </html>';
    }
}

?>
