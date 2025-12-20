<?php
/**
 * Page d'accueil publique
 * 
 * Affiche le catalogue des histoires publiées
 * Accessible à tous (connectés ou non)
 * Conforme aux bonnes pratiques vues en cours ProgServ2
 */

$pageTitle = "Découvrir les histoires";

require_once __DIR__ . '/../src/Classes/Database.php';
require_once __DIR__ . '/../src/config/app.php';
require_once __DIR__ . '/../src/i18n.php';

// Démarrage de la session pour vérifier si l'utilisateur est connecté (optionnel)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération de toutes les histoires publiées
try {
    $database = new Database();
    $pdo = $database->getPdo();

    // Requête pour récupérer toutes les histoires publiées avec leurs auteurs
    $sql = "SELECT 
                s.id,
                s.title,
                s.summary,
                s.published_at,
                u.username as author_name
            FROM stories s
            INNER JOIN users u ON s.author_id = u.id
            WHERE s.is_published = 1
            ORDER BY s.published_at DESC";

    $stmt = $pdo->prepare($sql);
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
    /* Styles spécifiques pour la page d'accueil */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 12px;
    }

    .page-header h1 {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .toolbar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        align-items: center;
    }

    .categories {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        justify-content: center;
    }

    .chip {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 500;
    }

    .chip:hover,
    .chip.active {
        background: white;
        color: #667eea;
        border-color: white;
    }

    .search {
        width: 100%;
        max-width: 500px;
    }

    .search input {
        width: 100%;
        padding: 1rem 1.5rem;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .search input:focus {
        outline: 3px solid rgba(255, 255, 255, 0.5);
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .cover {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        text-decoration: none;
    }

    .cover::before {
        content: '📖';
        font-size: 4rem;
        opacity: 0.3;
    }

    .badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        color: #667eea;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .title {
        margin: 0 0 0.5rem 0;
        font-size: 1.3rem;
    }

    .title a {
        color: #333;
        text-decoration: none;
        transition: color 0.3s;
    }

    .title a:hover {
        color: #667eea;
    }

    .author {
        color: #667eea;
        font-weight: 600;
        margin-bottom: 0.8rem;
        font-size: 0.95rem;
    }

    .summary {
        color: #666;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
    }

    .meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #999;
        font-size: 0.9rem;
        padding-top: 1rem;
        border-top: 1px solid #f0f0f0;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .dot {
        color: #ddd;
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
        margin-bottom: 1.5rem;
    }

    .btn-create {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .error-message {
        background: #ffebee;
        color: #c62828;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #c62828;
    }

    @media (max-width: 768px) {
        .page-header h1 {
            font-size: 1.8rem;
        }

        .grid {
            grid-template-columns: 1fr;
        }

        .categories {
            width: 100%;
        }

        .chip {
            flex: 1 1 auto;
        }
    }
</style>

<div class="container">
    <header class="page-header">
        <h1><?= t('discover') ?></h1>
        <div class="toolbar">
            <div class="categories">
                <button class="chip active"><?= t('all_categories') ?></button>
                <button class="chip"><?= t('genre_romance') ?></button>
                <button class="chip"><?= t('genre_horror') ?></button>
                <button class="chip"><?= t('genre_historical') ?></button>
                <button class="chip"><?= t('genre_action') ?></button>
                <button class="chip"><?= t('genre_fantasy') ?></button>
            </div>
            <form class="search" action="#" method="get">
                <input type="search" name="q" placeholder="<?= t('search_placeholder') ?>"
                    aria-label="<?= t('search') ?>">
            </form>
        </div>
    </header>

    <?php if (isset($errorMessage)): ?>
        <div class="error-message">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($stories)): ?>
        <div class="empty-state">
            <h2>Aucune histoire publiée pour le moment</h2>
            <p>Soyez le premier à partager une histoire !</p>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'author'): ?>
                <a href="<?= BASE_PATH ?>create_story.php" class="btn-create">
                    Créer ma première histoire
                </a>
            <?php elseif (!isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_PATH ?>register.php" class="btn-create">
                    S'inscrire pour écrire
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <section class="grid">
            <?php foreach ($stories as $story): ?>
                <article class="card">
                    <a class="cover" href="<?= BASE_PATH ?>read_story.php?id=<?= $story['id'] ?>">
                        <span class="badge"><?= t('genre_fantasy') ?></span>
                    </a>
                    <div class="card-body">
                        <h3 class="title">
                            <a href="<?= BASE_PATH ?>read_story.php?id=<?= $story['id'] ?>">
                                <?= htmlspecialchars($story['title']) ?>
                            </a>
                        </h3>
                        <p class="author">Par <?= htmlspecialchars($story['author_name']) ?></p>

                        <?php if (!empty($story['summary'])): ?>
                            <p class="summary">
                                <?= htmlspecialchars(mb_substr($story['summary'], 0, 120)) ?>
                                <?= mb_strlen($story['summary']) > 120 ? '...' : '' ?>
                            </p>
                        <?php endif; ?>

                        <div class="meta">
                            <span class="stat"><?= date('d/m/Y', strtotime($story['published_at'])) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>