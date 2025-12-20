<?php
/**
 * Page d'accueil publique
 * 
 * Affiche le catalogue des histoires publiées
 * Accessible à tous (connectés ou non)
 */

require_once __DIR__ . '/../src/Classes/Database.php';
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
            <input type="search" name="q" placeholder="<?= t('search_placeholder') ?>" aria-label="<?= t('search') ?>">
        </form>
    </div>
</header>

<?php if (isset($errorMessage)): ?>
    <div style="padding: 20px; background-color: #ffebee; color: #c62828; border-radius: 4px; margin: 20px 0;">
        <?= htmlspecialchars($errorMessage) ?>
    </div>
<?php endif; ?>

<?php if (empty($stories)): ?>
    <div style="text-align: center; padding: 60px 20px; color: #666;">
        <h2 style="margin-bottom: 15px; color: #999;">Aucune histoire publiée pour le moment</h2>
        <p>Soyez le premier à partager une histoire !</p>
        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'author'): ?>
            <p style="margin-top: 20px;">
                <a href="create_story.php"
                    style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
                    Créer ma première histoire
                </a>
            </p>
        <?php elseif (!isset($_SESSION['user_id'])): ?>
            <p style="margin-top: 20px;">
                <a href="register.php" style="color: #4CAF50;">Inscrivez-vous</a> pour créer des histoires !
            </p>
        <?php endif; ?>
    </div>
<?php else: ?>
    <section class="grid">
        <?php foreach ($stories as $story): ?>
            <article class="card">
                <a class="cover" href="read_story.php?id=<?= $story['id'] ?>">
                    <!-- Badge optionnel : vous pouvez ajouter des catégories plus tard -->
                    <span class="badge"><?= t('genre_fantasy') ?></span>
                </a>
                <div class="card-body">
                    <h3 class="title">
                        <a href="read_story.php?id=<?= $story['id'] ?>">
                            <?= htmlspecialchars($story['title']) ?>
                        </a>
                    </h3>
                    <p class="author"><?= htmlspecialchars($story['author_name']) ?></p>

                    <!-- Affichage du résumé (optionnel) -->
                    <?php if (!empty($story['summary'])): ?>
                        <p class="summary" style="margin: 10px 0; color: #666; font-size: 14px; line-height: 1.4;">
                            <?= htmlspecialchars(mb_substr($story['summary'], 0, 100)) ?>
                            <?= mb_strlen($story['summary']) > 100 ? '...' : '' ?>
                        </p>
                    <?php endif; ?>

                    <div class="meta">
                        <!-- Vous pouvez ajouter des stats plus tard -->
                        <span class="stat" title="Date de publication">
                            <?= date('d/m/Y', strtotime($story['published_at'])) ?>
                        </span>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php include __DIR__ . '/templates/footer.php'; ?>