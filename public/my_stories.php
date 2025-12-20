<?php
/**
 * Page "Mes histoires"
 */

$pageTitle = "Mes histoires";

require_once __DIR__ . '/../src/Classes/Database.php';
require_once __DIR__ . '/../src/config/app.php';
require_once __DIR__ . '/auth_check.php';

// Vérification du rôle
if ($_SESSION['role'] !== 'author') {
    http_response_code(403);
    die('Accès refusé. Seuls les auteurs peuvent accéder à cette page.');
}

// Récupération des histoires
try {
    $database = new Database();
    $pdo = $database->getPdo();

    $sql = "SELECT 
                id, title, summary, is_published, published_at, created_at, updated_at
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

include __DIR__ . '/templates/header.php';
?>

<style>
    .page-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-title h1 {
        font-size: 2rem;
        margin: 0;
    }

    .btn-new {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        border: 2px solid white;
    }

    .btn-new:hover {
        background: white;
        color: #667eea;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        color: #666;
        margin-top: 0.5rem;
        font-size: 0.95rem;
    }

    .story-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s;
    }

    .story-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
    }

    .story-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .story-title {
        font-size: 1.5rem;
        color: #333;
        margin: 0;
        flex: 1;
    }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-published {
        background: #e8f5e9;
        color: #4caf50;
    }

    .status-draft {
        background: #fff3e0;
        color: #ff9800;
    }

    .story-summary {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .story-meta {
        font-size: 0.9rem;
        color: #999;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .story-actions {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-read {
        background: #e3f2fd;
        color: #1976d2;
    }

    .btn-read:hover {
        background: #1976d2;
        color: white;
    }

    .btn-edit {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .btn-edit:hover {
        background: #7b1fa2;
        color: white;
    }

    .btn-delete {
        background: #ffebee;
        color: #d32f2f;
    }

    .btn-delete:hover {
        background: #d32f2f;
        color: white;
    }

    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .empty-state h2 {
        color: #999;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #666;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .page-title {
            flex-direction: column;
            text-align: center;
        }

        .story-header {
            flex-direction: column;
        }

        .story-actions {
            flex-direction: column;
        }

        .btn-action {
            text-align: center;
        }
    }
</style>

<div class="container">
    <div class="page-title">
        <h1>📚 Mes histoires</h1>
        <a href="<?= BASE_PATH ?>create_story.php" class="btn-new">➕ Nouvelle histoire</a>
    </div>

    <?php if (isset($errorMessage)): ?>
        <div style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <?php
    $totalStories = count($stories);
    $publishedStories = array_filter($stories, fn($s) => $s['is_published']);
    $draftStories = array_filter($stories, fn($s) => !$s['is_published']);
    $publishedCount = count($publishedStories);
    $draftCount = count($draftStories);
    ?>

    <?php if ($totalStories > 0): ?>
        <div class="stats-grid">
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
                        <?= $story['is_published'] ? '✓ Publiée' : '📝 Brouillon' ?>
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
                    <a href="<?= BASE_PATH ?>read_story.php?id=<?= $story['id'] ?>" class="btn-action btn-read">
                        👁 Lire
                    </a>
                    <a href="<?= BASE_PATH ?>edit_story.php?id=<?= $story['id'] ?>" class="btn-action btn-edit">
                        ✏️ Modifier
                    </a>
                    <a href="<?= BASE_PATH ?>delete_story.php?id=<?= $story['id'] ?>" class="btn-action btn-delete"
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
            <a href="<?= BASE_PATH ?>create_story.php" class="btn-new" style="display: inline-block;">
                ➕ Créer ma première histoire
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>