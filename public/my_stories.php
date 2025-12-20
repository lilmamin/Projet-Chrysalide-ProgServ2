<?php
/**
 * Page "Mes histoires"
 * 
 * Affiche la liste de toutes les histoires créées par l'auteur connecté
 * Permet d'accéder aux actions : modifier, supprimer, publier/dépublier
 * Page protégée - réservée aux utilisateurs avec le rôle "author"
 * Conforme aux bonnes pratiques vues en cours ProgServ2
 */

require_once __DIR__ . '/../src/Classes/Database.php';

// Vérification de l'authentification
require_once __DIR__ . '/auth_check.php';

// Vérification du rôle : seuls les auteurs peuvent voir cette page
if ($_SESSION['role'] !== 'author') {
    http_response_code(403);
    die('Accès refusé. Seuls les auteurs peuvent accéder à cette page.');
}

// Récupération des histoires de l'auteur connecté
try {
    $database = new Database();
    $pdo = $database->getPdo();

    // Requête pour récupérer toutes les histoires de l'auteur
    $sql = "SELECT 
                id,
                title,
                summary,
                is_published,
                published_at,
                created_at,
                updated_at
            FROM stories 
            WHERE author_id = :author_id
            ORDER BY created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $stories = $stmt->fetchAll();

} catch (PDOException $e) {
    $errorMessage = "Erreur lors de la récupération des histoires : " . $e->getMessage();
    $stories = [];
} catch (Exception $e) {
    $errorMessage = "Erreur inattendue : " . $e->getMessage();
    $stories = [];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes histoires - Chrysalide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .back-link {
            color: #4CAF50;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 14px;
        }

        .btn-edit {
            background-color: #2196F3;
        }

        .btn-edit:hover {
            background-color: #0b7dda;
        }

        .btn-delete {
            background-color: #f44336;
        }

        .btn-delete:hover {
            background-color: #da190b;
        }

        .error-box {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state h2 {
            margin-bottom: 15px;
            color: #999;
        }

        .story-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.2s;
        }

        .story-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .story-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            gap: 15px;
        }

        .story-title {
            font-size: 22px;
            color: #333;
            margin: 0;
            flex: 1;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-published {
            background-color: #4CAF50;
            color: white;
        }

        .status-draft {
            background-color: #FF9800;
            color: white;
        }

        .story-summary {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .story-meta {
            font-size: 14px;
            color: #999;
            margin-bottom: 15px;
        }

        .story-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
        }

        .stat-label {
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div>
                <a href="dashboard.php" class="back-link">← Retour au tableau de bord</a>
                <h1 style="margin-top: 10px;">Mes histoires</h1>
            </div>
            <a href="create_story.php" class="btn">+ Nouvelle histoire</a>
        </div>

        <?php if (isset($errorMessage)): ?>
            <div class="error-box">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>

        <?php
        // Calcul des statistiques
        $totalStories = count($stories);
        $publishedStories = array_filter($stories, fn($s) => $s['is_published']);
        $draftStories = array_filter($stories, fn($s) => !$s['is_published']);
        $publishedCount = count($publishedStories);
        $draftCount = count($draftStories);
        ?>

        <?php if ($totalStories > 0): ?>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?= $totalStories ?></div>
                    <div class="stat-label">Total d'histoires</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $publishedCount ?></div>
                    <div class="stat-label">Publiées</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $draftCount ?></div>
                    <div class="stat-label">Brouillons</div>
                </div>
            </div>

            <?php foreach ($stories as $story): ?>
                <div class="story-card">
                    <div class="story-header">
                        <h2 class="story-title"><?= htmlspecialchars($story['title']) ?></h2>
                        <span class="status-badge <?= $story['is_published'] ? 'status-published' : 'status-draft' ?>">
                            <?= $story['is_published'] ? 'Publiée' : 'Brouillon' ?>
                        </span>
                    </div>

                    <div class="story-summary">
                        <?= htmlspecialchars($story['summary']) ?>
                    </div>

                    <div class="story-meta">
                        Créée le <?= date('d/m/Y à H:i', strtotime($story['created_at'])) ?>
                        <?php if ($story['is_published'] && $story['published_at']): ?>
                            • Publiée le <?= date('d/m/Y à H:i', strtotime($story['published_at'])) ?>
                        <?php endif; ?>
                        <?php if ($story['updated_at'] !== $story['created_at']): ?>
                            • Modifiée le <?= date('d/m/Y à H:i', strtotime($story['updated_at'])) ?>
                        <?php endif; ?>
                    </div>

                    <div class="story-actions">
                        <a href="read_story.php?id=<?= $story['id'] ?>" class="btn btn-small">
                            👁 Lire
                        </a>
                        <a href="edit_story.php?id=<?= $story['id'] ?>" class="btn btn-small btn-edit">
                            ✏️ Modifier
                        </a>
                        <a href="delete_story.php?id=<?= $story['id'] ?>" class="btn btn-small btn-delete"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette histoire ? Cette action est irréversible.');">
                            🗑 Supprimer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="empty-state">
                <h2>Aucune histoire pour le moment</h2>
                <p>Vous n'avez pas encore créé d'histoire.</p>
                <p style="margin-top: 20px;">
                    <a href="create_story.php" class="btn">Créer ma première histoire</a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>