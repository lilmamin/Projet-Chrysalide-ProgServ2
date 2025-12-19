<?php
$config = parse_ini_file(__DIR__ . '/src/config/database.ini', true);

if (!$config) {
    die('Impossible de lire database.ini');
}

$db = $config['database'];

$dsn = sprintf(
    '%s:host=%s;dbname=%s;charset=%s',
    $db['driver'],
    $db['host'],
    $db['dbname'],
    $db['charset'] ?? 'utf8mb4'
);

try {
    $pdo = new PDO($dsn, $db['user'], $db['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "Connexion OK ✅";
} catch (PDOException $e) {
    echo "ÉCHEC CONNEXION ❌<br>";
    echo $e->getMessage();
}
