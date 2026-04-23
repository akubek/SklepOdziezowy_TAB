<?php

/**
 * Safely display text data in HTML (prevent XSS)
 */
function e(?string $text): string {
    // if null return empty string
    if ($text === null) {
        return '';
    }
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function renderView(string $viewName, array $data = []) {
    extract($data); // Zmienne (np. $title, $products) będą dostępne we wszystkich 3 plikach
    
    require BASE_PATH . '/views/partials/header.php';
    require BASE_PATH . "/views/{$viewName}.php";
    require BASE_PATH . '/views/partials/footer.php';
}
