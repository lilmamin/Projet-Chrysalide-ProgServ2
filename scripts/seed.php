<?php
// scripts/seed.php

const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../src/config/database.ini';

// 1. Charger la config
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);
if (!$config) {
    die("Impossible de lire database.ini\n");
}

$dbConf = $config['database'] ?? null;
if (!$dbConf) {
    die("Section [database] manquante dans database.ini\n");
}

// 2. Connexion PDO
$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=utf8mb4',
    $dbConf['host'],
    $dbConf['dbname']
);

try {
    $pdo = new PDO($dsn, $dbConf['user'], $dbConf['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage() . "\n");
}

// 3. Vider les tables (ordre important à cause des clés étrangères)
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE likes");
$pdo->exec("TRUNCATE TABLE library");
$pdo->exec("TRUNCATE TABLE comments");
$pdo->exec("TRUNCATE TABLE chapters");
$pdo->exec("TRUNCATE TABLE stories");
$pdo->exec("TRUNCATE TABLE users");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

// 4. Insérer quelques users
$passwordHash = password_hash('password123', PASSWORD_DEFAULT);

$insertUser = $pdo->prepare("
    INSERT INTO users (name, email, password, role)
    VALUES (:name, :email, :password, :role)
");

$insertUser->execute([
    'name' => 'Alice Auteur',
    'email' => 'alice@example.com',
    'password' => $passwordHash,
    'role' => 'author'
]);

$authorId = (int) $pdo->lastInsertId();

$insertUser->execute([
    'name' => 'Léo Lecteur',
    'email' => 'leo@example.com',
    'password' => $passwordHash,
    'role' => 'reader'
]);

$readerId = (int) $pdo->lastInsertId();

// 5. Insérer une story
$insertStory = $pdo->prepare("
    INSERT INTO stories (user_id, title, summary, cover_image, is_completed, published_at)
    VALUES (:user_id, :title, :summary, :cover_image, :is_completed, :published_at)
");

$insertStory->execute([
    'user_id' => $authorId,
    'title' => 'La Nuit des Métamorphoses',
    'summary' => 'Une histoire où chaque choix transforme le monde un peu plus.',
    'cover_image' => '/uploads/covers/metamorphoses.jpg',
    'is_completed' => 0,
    'published_at' => date('Y-m-d H:i:s')
]);

$storyId = (int) $pdo->lastInsertId();

// 6. Insérer quelques chapitres
$insertChapter = $pdo->prepare("
    INSERT INTO chapters (story_id, chapter_number, title, content)
    VALUES (:story_id, :chapter_number, :title, :content)
");

$chapters = [
    [1, 'Prologue', 'Ceci est le contenu du premier chapitre...'],
    [2, 'Le Premier Choix', 'Le héros doit choisir sa voie...'],
    [3, 'Le Point de Rupture', 'Tout bascule ici...'],
];

foreach ($chapters as [$num, $title, $content]) {
    $insertChapter->execute([
        'story_id' => $storyId,
        'chapter_number' => $num,
        'title' => $title,
        'content' => $content
    ]);
}

// 7. Ajouter la story à la bibliothèque du lecteur
$insertLibrary = $pdo->prepare("
    INSERT INTO library (user_id, story_id, created_at)
    VALUES (:user_id, :story_id, :created_at)
");
$insertLibrary->execute([
    'user_id' => $readerId,
    'story_id' => $storyId,
    'created_at' => date('Y-m-d H:i:s')
]);

// 8. Ajouter un like
$insertLike = $pdo->prepare("
    INSERT INTO likes (user_id, story_id, created_at)
    VALUES (:user_id, :story_id, :created_at)
");
$insertLike->execute([
    'user_id' => $readerId,
    'story_id' => $storyId,
    'created_at' => date('Y-m-d H:i:s')
]);

