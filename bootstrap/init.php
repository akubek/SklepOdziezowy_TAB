<?php
// bootstrap/init.php

ob_start();

// 2. INICJALIZACJA SESJI
if (session_status() === PHP_SESSION_NONE) {
    // Zabezpieczenie przed atakami typu Session Fixation
    ini_set('session.use_strict_mode', 1);
    session_start();
}

// 3. RAPORTOWANIE BŁĘDÓW (Wygoda dla programisty)
// W przyszłości możesz to wyłączyć na produkcji: error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 4. GLOBALNE USTAWIENIA (np. strefa czasowa dla poprawnych dat zamówień)
date_default_timezone_set('Europe/Warsaw');

$envPath = __DIR__ . '/../.env';

if (file_exists($envPath)) {
    $parsedEnv = parse_ini_file($envPath);
    if ($parsedEnv) {
        foreach ($parsedEnv as $key => $value) {
            $_ENV[$key] = $value;
        }
    } else {
        die("CRITICAL ERROR: .env file is corrupted or has wrong format!");
    }
} else {
    // Bez tego pliku nie mamy haseł do bazy, więc nie ma sensu iść dalej
    die("CRITICAL ERROR: no .env file detected!");
}
