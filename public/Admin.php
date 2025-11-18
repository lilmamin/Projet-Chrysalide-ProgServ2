<?php
// Constantes
const DATABASE_FILE = __DIR__ . '/../users.db';

// Démarre la session
session_start();

// Vérifie si l'utilisateur est authentifié
if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: public/login.php');
    exit();
}

// Vérifie si l'utilisateur a le bon rôle
if ($_SESSION['role'] !== 'admin') {
    // Redirige vers la page 403 si l'utilisateur n'est pas admin
    header('Location: 403.php');
    exit();
}

// Sinon, récupère les autres informations de l'utilisateur
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Récupère la liste de tous les utilisateurs (fonctionnalité d'administration)
try {
    $pdo = new PDO('sqlite:' . DATABASE_FILE);

    $stmt = $pdo->query('SELECT * FROM users ORDER BY id');
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    $error = 'Erreur lors de la récupération des utilisateurs : ' . $e->getMessage();
}
?>