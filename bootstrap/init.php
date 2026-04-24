<?php
// bootstrap/init.php
define('BASE_PATH', dirname(__DIR__));

error_reporting(E_ALL);
ini_set('display_errors', '0');

$envPath = BASE_PATH . '/.env';
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
    // cannot start without env
    die("CRITICAL ERROR: no .env file detected!");
}

date_default_timezone_set('Europe/Warsaw');

ob_start();
if (session_status() === PHP_SESSION_NONE) {
    // Session Fixation
    ini_set('session.use_strict_mode', 1);
    session_start();
}

require_once BASE_PATH . '/src/helpers.php';

