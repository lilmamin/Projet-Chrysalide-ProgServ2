<?php
require_once __DIR__ . '/../src/Classes/Database.php';

try {
    $database = new Database();
    echo "✅ Connexion à la base de données réussie !";
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}