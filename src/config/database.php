<?php
// src/config/database.php
// Ce fichier est inclus par src/database.php et crée la variable $pdo

$cfgPath = __DIR__ . '/database.ini';
if (!is_file($cfgPath)) {
    throw new RuntimeException("Fichier de config DB introuvable: $cfgPath");
}

$cfgAll = parse_ini_file($cfgPath, true);
if (!$cfgAll || empty($cfgAll['database'])) {
    throw new RuntimeException("Section [database] manquante dans $cfgPath");
}

$db = $cfgAll['database'];

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $db['host'] ?? '127.0.0.1',
    (int) ($db['port'] ?? 3306),
    $db['dbname'] ?? '',
    $db['charset'] ?? 'utf8mb4'
);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $db['username'] ?? '', $db['password'] ?? '', $options);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Erreur de connexion à la base de données : ' . $e->getMessage());
}



//-----


class Database implements DatabaseInterface
{
    const DATABASE_FILE = __DIR__ . '/../../myapp.db';

    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("sqlite:" . self::DATABASE_FILE);

        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            age INTEGER NOT NULL
        );";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}