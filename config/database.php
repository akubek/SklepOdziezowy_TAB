<?php
// config/database.php

return [
    'host'     => $_ENV['DB_HOST'] ?? 'db', // 'db' default Docker host
    'port'     => $_ENV['DB_PORT'] ?? '5432',
    'database' => $_ENV['DB_NAME'] ?? '',
    'username' => $_ENV['DB_USER'] ?? '',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
];
