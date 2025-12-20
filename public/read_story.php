<?php
/**
 * Page de lecture d'une histoire
 */

require_once __DIR__ . '/../src/Classes/Database.php';
require_once __DIR__ . '/../src/config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification de la présence de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ' . BASE_PATH);
    exit();
}

$storyId = (int) $_GET['id'];

try {
    $database = new Database();
    $pdo = $database->getPdo();

    $sql = "SELECT 
                s.id, s.title, s.summary, s.content, s.is_published, 
                s.published_at, s.created_at, s.author_id,
                u.username as author_name
            FROM stories s
            INNER JOIN users u ON s.author_id = u.id
            WHERE s.id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $storyId, PDO::PARAM_INT);
    $stmt->execute();

    $story = $stmt->fetch();

    if (!$story) {
        header('Location: ' . BASE_PATH);
        exit();
    }

    // Vérification de la publication
    if (!$story['is_published']) {
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

$isAuthor = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $story['author_id'];
$pageTitle = htmlspecialchars($story['title']);

include __DIR__ . '/templates/header.php';
?>

<style>
    .reading-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.3s;
    }

    .back-link:hover {
        gap: 0.8rem;
    }

    .story-header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    }

    .story-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        line-height: 1.3;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .story-meta {
        font-size: 1rem;
        opacity: 0.95;
        margin-bottom: 1.5rem;
    }

    .author-name {
        font-weight: 700;
        text-decoration: underline;
    }

    .story-summary {
        font-size: 1.1rem;
        font-style: italic;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        border-left: 4px solid rgba(255, 255, 255, 0.5);
        line-height: 1.6;
    }

    .story-content-card {
        background: white;
        padding: 3rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .story-content {
        font-family: Georgia, 'Times New Roman', serif;
        font-size: 1.2rem;
        color: #333;
        line-height: 1.9;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: pre-wrap;
        max-width: 100%;
    }

    .story-content p {
        margin-bottom: 1.5rem;
    }

    .author-actions-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        border-left: 4px solid #667eea;
    }

    .author-actions-card h3 {
        color: #667eea;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-edit {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .btn-edit:hover {
        background: #7b1fa2;
        color: white;
    }

    .btn-stories {
        background: #e3f2fd;
        color: #1976d2;
    }

    .btn-stories:hover {
        background: #1976d2;
        color: white;
    }

    .story-footer-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        text-align: center;
        color: #666;
    }

    .story-footer-card p {
        margin-bottom: 0.5rem;
    }

    .story-footer-card a {
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
    }

    .story-footer-card a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .reading-container {
            padding: 0 1rem;
        }

        .story-header-card {
            padding: 2rem 1.5rem;
        }

        .story-title {
            font-size: 1.8rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .story-content-card {
            padding: 2rem 1.5rem;
        }

        .story-content {
            font-size: 1.1rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container reading-container">
    <a href="<?= BASE_PATH ?>" class="back-link">← Retour aux histoires</a>

    <div class="story-header-card">
        <h1 class="story-title">
            <?= htmlspecialchars($story['title']) ?>
            <?php if (!$story['is_published']): ?>
                <span class="status-badge">📝 Brouillon</span>
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

    <div class="story-content-card">
        <div class="story-content">
            <?php
            // Afficher le contenu avec préservation des retours à la ligne
            $content = htmlspecialchars($story['content']);
            $content = nl2br($content);
            echo $content;
            ?>
        </div>
    </div>

    <?php if ($isAuthor): ?>
        <div class="author-actions-card">
            <h3>⚙️ Actions de l'auteur</h3>
            <div class="action-buttons">
                <a href="<?= BASE_PATH ?>edit_story.php?id=<?= $story['id'] ?>" class="btn-action btn-edit">
                    ✏️ Modifier cette histoire
                </a>
                <a href="<?= BASE_PATH ?>my_stories.php" class="btn-action btn-stories">
                    📚 Mes histoires
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="story-footer-card">
        <p>
            Cette histoire fait partie de <strong>Chrysalide</strong>,
            la plateforme de lecture et d'écriture collaborative.
        </p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <p style="margin-top: 1rem;">
                <a href="<?= BASE_PATH ?>register.php">Inscrivez-vous</a>
                pour créer vos propres histoires !
            </p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>