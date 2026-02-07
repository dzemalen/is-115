<?php
// config/db.inc.php – tilkobling til databasen for søknadssystemet

$envPath = dirname(__DIR__) . '/.env';
if (is_readable($envPath)) {
    $env = parse_ini_file($envPath, false, INI_SCANNER_TYPED);
    if (is_array($env)) {
        foreach ($env as $key => $value) {
            if (getenv($key) === false) {
                putenv($key . '=' . $value);
            }
        }
    }
}

define('DB_VERT',   getenv('DB_HOST') ?: 'localhost');
define('DB_BRUKER', getenv('DB_USER') ?: 'root');
define('DB_PASS',   getenv('DB_PASS') ?: '');
define('DB_NAVN',   getenv('DB_NAME') ?: 'soknadssystem');


$dsn = 'mysql:host=' . DB_VERT . ';dbname=' . DB_NAVN . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, DB_BRUKER, DB_PASS);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    
    echo "Feil ved tilkoblingen til databasen.";
    
    exit;
}
