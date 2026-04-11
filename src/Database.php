<?php
class Database {
    private static $pdo = null;

    public static function getConnection() {
        // Jeśli nie mamy jeszcze połączenia, tworzymy je
        if (self::$pdo === null) {
            // 1. Dynamiczne wczytanie haseł z pliku .env!
            $env = parse_ini_file(__DIR__ . '/../.env');
            
            if (!$env) {
                die("Błąd: Nie znaleziono pliku .env lub jest on pusty!");
            }

            // 2. Przypisanie zmiennych
            $host = 'db'; // Sztywna nazwa kontenera Dockera
            $db   = $env['DB_NAME'];
            $user = $env['DB_USER'];
            $pass = $env['DB_PASSWORD'];
            
            $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
            
            // 3. Próba połączenia
            try {
                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die("<h2>Błąd połączenia z bazą danych!</h2><p>" . htmlspecialchars($e->getMessage()) . "</p>");
            }
        }

        // Zwracamy gotowy obiekt połączenia
        return self::$pdo;
    }
}
?>