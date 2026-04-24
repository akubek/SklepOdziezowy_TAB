<?php

/**
 * Safely display text data in HTML (prevent XSS)
 */
function e(?string $text): string
{
    // if null return empty string
    if ($text === null) {
        return '';
    }
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function renderView(string $viewName, array $data = [])
{
    global $container;
    extract($data, EXTR_SKIP); // Zmienne (np. $title, $products) będą dostępne we wszystkich 3 plikach

    ob_start();
    require BASE_PATH . "/views/{$viewName}.php";
    $content = ob_get_clean();
    require BASE_PATH . '/views/layouts/main.php'; // uses $content and possibly $container
}
