<?php
/**
 * Page de lecture d'une histoire
 * 
 * Affiche le contenu complet d'une histoire publiée
 * Page publique - accessible à tous (connectés ou non)
 * Seules les histoires publiées sont accessibles
 */

require_once __DIR__ . '/../src/Classes/Database.php';

// Démarrage de la session pour vérifier si l'utilisateur est connecté (optionnel)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification de la présence de l'ID de l'histoire
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$storyId = (int) $_GET['id'];

try {
    // Connexion à la base de données
    $database = new Database();
    $pdo = $database->getPdo();

    // Récupération de l'histoire avec les informations de l'auteur
    $sql = "SELECT 
                s.id,
                s.title,
                s.summary,
                s.content,
                s.is_published,
                s.published_at,
                s.created_at,
                s.author_id,
                u.username as author_name
            FROM stories s
            INNER JOIN users u ON s.author_id = u.id
            WHERE s.id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $storyId, PDO::PARAM_INT);
    $stmt->execute();

    $story = $stmt->fetch();

    // Si l'histoire n'existe pas
    if (!$story) {
        header('Location: index.php');
        exit();
    }

    // Vérification de la publication
    // Si l'histoire n'est pas publiée, seul l'auteur peut la voir
    if (!$story['is_published']) {
        // Vérifier si l'utilisateur connecté est l'auteur
        $isAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $story['author_id'];

        if (!$isAuthor) {
            http_response_code(403);
            die('Cette histoire n\'est pas encore publiée.');
        }
    }

} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'histoire : " . $e->getMessage());
} catch (Exception $e) {
    die("Erreur inattendue : " . $e->getMessage());
}

// Vérifier si l'utilisateur connecté est l'auteur de cette histoire
$isAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $story['author_id'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($story['title']) ?> - Chrysalide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Georgia, 'Times New Roman', serif;
            background-color: #f9f9f9;
            padding: 20px;
            line-height: 1.8;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 30px;
            color: #4CAF50;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .story-header {
            border-bottom: 2px solid #eee;
            padding-bottom: 30px;
            margin-bottom: 30px;
        }

        .story-title {
            font-size: 36px;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .story-meta {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .author-name {
            color: #4CAF50;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }

        .status-draft {
            background-color: #FF9800;
            color: white;
        }

        .story-summary {
            font-size: 18px;
            color: #555;
            font-style: italic;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f5f5f5;
            border-left: 4px solid #4CAF50;
        }

        .story-content {
            font-size: 18px;
            color: #333;
            line-height: 1.8;
            text-align: justify;
        }

        .story-content p {
            margin-bottom: 20px;
        }

        .author-actions {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-right: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-edit {
            background-color: #2196F3;
        }

        .btn-edit:hover {
            background-color: #0b7dda;
        }

        .story-footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            text-align: center;
            font-family: Arial, sans-serif;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="index.php" class="back-link">← Retour aux histoires</a>

        <div class="story-header">
            <h1 class="story-title">
                <?= htmlspecialchars($story['title']) ?>
                <?php if (!$story['is_published']): ?>
                    <span class="status-badge status-draft">Brouillon</span>
                <?php endif; ?>
            </h1>

            <div class="story-meta">
                Par <span class="author-name"><?= htmlspecialchars($story['author_name']) ?></span>
                <?php if ($story['is_published'] && $story['published_at']): ?>
                    • Publié le <?= date('d/m/Y', strtotime($story['published_at'])) ?>
                <?php else: ?>
                    • Créé le <?= date('d/m/Y', strtotime($story['created_at'])) ?>
                <?php endif; ?>
            </div>

            <div class="story-summary">
                <?= htmlspecialchars($story['summary']) ?>
            </div>
        </div>

        <div class="story-content">
            <?php
            // Afficher le contenu avec préservation des retours à la ligne
            $content = htmlspecialchars($story['content']);
            $content = nl2br($content);
            echo $content;
            ?>
        </div>

        <?php if ($isAuthor): ?>
            <div class="author-actions">
                <p style="color: #666; margin-bottom: 15px; font-family: Arial, sans-serif;">
                    <strong>Actions de l'auteur :</strong>
                </p>
                <a href="edit_story.php?id=<?= $story['id'] ?>" class="btn btn-edit">
                    ✏️ Modifier cette histoire
                </a>
                <a href="my_stories.php" class="btn">
                    📚 Mes histoires
                </a>
            </div>
        <?php endif; ?>

        <div class="story-footer">
            <p>
                Cette histoire fait partie de Chrysalide,
                la plateforme de lecture et d'écriture collaborative.
            </p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <p style="margin-top: 10px;">
                    <a href="register.php" style="color: #4CAF50;">Inscrivez-vous</a>
                    pour créer vos propres histoires !
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>