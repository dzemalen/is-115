<?php
// config/db.inc.php – tilkobling til databasen for søknadssystemet

define('DB_VERT',   'localhost');
define('DB_BRUKER', 'root');
define('DB_PASS',   '');
define('DB_NAVN',   'soknadssystem');


$dsn = 'mysql:host=' . DB_VERT . ';dbname=' . DB_NAVN . ';charset=utf8mb4';

try {
    $pdo = new PDO($dsn, DB_BRUKER, DB_PASS);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    
    echo "Feil ved tilkoblingen til databasen.";
    
    exit;
}
