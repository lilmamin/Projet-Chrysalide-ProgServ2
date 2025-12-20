<?php
/**
 * Page d'accueil (dashboard privé)
 * 
 * Page protégée accessible uniquement aux utilisateurs authentifiés
 * Affiche les informations de l'utilisateur connecté
 */

$pageTitle = "Mon espace";

require_once __DIR__ . '/../src/config/app.php';
require_once __DIR__ . '/auth_check.php';

include __DIR__ . '/templates/header.php';
?>

<style>
    .dashboard-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .welcome-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    }

    .welcome-card h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .welcome-card p {
        opacity: 0.95;
        font-size: 1.1rem;
    }

    .user-info-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .user-info-card h2 {
        margin-bottom: 1.5rem;
        color: #333;
        font-size: 1.5rem;
    }

    .info-grid {
        display: grid;
        gap: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .info-label {
        font-weight: 600;
        color: #555;
        min-width: 150px;
    }

    .info-value {
        color: #333;
        flex: 1;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .role-reader {
        background: #e3f2fd;
        color: #1976d2;
    }

    .role-author {
        background: #fff3e0;
        color: #f57c00;
    }

    .status-confirmed {
        color: #4caf50;
        font-weight: 600;
    }

    .status-pending {
        color: #ff9800;
        font-weight: 600;
    }

    .actions-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .actions-card h2 {
        margin-bottom: 1.5rem;
        color: #333;
        font-size: 1.5rem;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .btn-action {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-logout {
        background: linear-gradient(135deg, #f44336 0%, #e91e63 100%);
        box-shadow: 0 4px 15px rgba(244, 67, 54, 0.2);
    }

    .btn-logout:hover {
        box-shadow: 0 6px 20px rgba(244, 67, 54, 0.3);
    }

    @media (max-width: 768px) {
        .welcome-card h1 {
            font-size: 1.5rem;
        }

        .action-buttons {
            grid-template-columns: 1fr;
        }

        .info-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-label {
            min-width: auto;
        }
    }
</style>

<div class="container dashboard-container">
    <div class="welcome-card">
        <h1>👋 <?= t('welcome') ?>, <?= htmlspecialchars($_SESSION['username']) ?> !</h1>
        <p><?= $lang === 'fr' ? 'Ravi de vous revoir sur Chrysalide' : 'Nice to see you back on Chrysalide' ?></p>
    </div>

    <div class="user-info-card">
        <h2>📋 <?= $lang === 'fr' ? 'Vos informations' : 'Your Information' ?></h2>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label"><?= t('username') ?></span>
                <span class="info-value"><?= htmlspecialchars($_SESSION['username']) ?></span>
            </div>

            <div class="info-item">
                <span class="info-label"><?= t('email') ?></span>
                <span class="info-value"><?= htmlspecialchars($_SESSION['email']) ?></span>
            </div>

            <div class="info-item">
                <span class="info-label"><?= t('role') ?></span>
                <span class="info-value">
                    <?php if ($_SESSION['role'] === 'author'): ?>
                        <span class="role-badge role-author">✍️ <?= t('author') ?></span>
                    <?php else: ?>
                        <span class="role-badge role-reader">📚 <?= t('reader') ?></span>
                    <?php endif; ?>
                </span>
            </div>

            <div class="info-item">
                <span class="info-label"><?= $lang === 'fr' ? 'Statut du compte' : 'Account Status' ?></span>
                <span class="info-value">
                    <?php if ($_SESSION['is_confirmed']): ?>
                        <span class="status-confirmed">✓ <?= t('account_confirmed') ?></span>
                    <?php else: ?>
                        <span class="status-pending">⚠ <?= t('account_pending') ?></span>
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>

    <div class="actions-card">
        <h2>🚀 <?= $lang === 'fr' ? 'Actions rapides' : 'Quick Actions' ?></h2>
        <div class="action-buttons">
            <?php if ($_SESSION['role'] === 'author'): ?>
                <a href="<?= BASE_PATH ?>my_stories.php" class="btn-action">
                    📚 <?= t('my_stories') ?>
                </a>
                <a href="<?= BASE_PATH ?>create_story.php" class="btn-action">
                    ➕ <?= t('new_story') ?>
                </a>
            <?php endif; ?>

            <a href="<?= BASE_PATH ?>" class="btn-action">
                🔍 <?= t('discover') ?>
            </a>

            <a href="<?= BASE_PATH ?>logout.php" class="btn-action btn-logout">
                🚪 <?= t('logout') ?>
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>