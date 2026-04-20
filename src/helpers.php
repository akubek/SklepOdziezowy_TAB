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
?>
