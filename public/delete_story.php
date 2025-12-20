<?php
/**
 * Page de suppression d'histoire
 * 
 * Permet aux auteurs de supprimer une de leurs histoires
 * Page protégée - réservée aux utilisateurs avec le rôle "author"
 * Sécurité : un auteur ne peut supprimer que ses propres histoires
 */

require_once __DIR__ . '/../src/Classes/Database.php';

// Vérification de l'authentification
require_once __DIR__ . '/../src/config/app.php';
require_once __DIR__ . '/auth_check.php';

// Vérification du rôle : seuls les auteurs peuvent supprimer des histoires
if ($_SESSION['role'] !== 'author') {
    http_response_code(403);
    die('Accès refusé. Seuls les auteurs peuvent supprimer des histoires.');
}

// Vérification de la présence de l'ID de l'histoire
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: my_stories.php');
    exit();
}

$storyId = (int) $_GET['id'];

try {
    // Connexion à la base de données
    $database = new Database();
    $pdo = $database->getPdo();

    // Vérification que l'histoire existe et appartient à l'auteur connecté
    $sql = "SELECT id, title FROM stories WHERE id = :id AND author_id = :author_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $storyId, PDO::PARAM_INT);
    $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $story = $stmt->fetch();

    // Si l'histoire n'existe pas ou n'appartient pas à l'auteur
    if (!$story) {
        header('Location: my_stories.php');
        exit();
    }

    // Suppression de l'histoire
    $sql = "DELETE FROM stories WHERE id = :id AND author_id = :author_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $storyId, PDO::PARAM_INT);
    $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    // Redirection vers la liste des histoires avec message de succès
    // Note : dans une vraie application, on utiliserait des sessions flash pour le message
    header('Location: my_stories.php');
    exit();

} catch (PDOException $e) {
    die("Erreur lors de la suppression de l'histoire : " . $e->getMessage());
} catch (Exception $e) {
    die("Erreur inattendue : " . $e->getMessage());
}