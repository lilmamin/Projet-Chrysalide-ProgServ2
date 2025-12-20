<?php
require_once __DIR__ . '/../../src/config/app.php';
require_once __DIR__ . '/../../src/i18n.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang ?? 'fr') ?>">

<head>
    <meta charset="utf-8">
    <title><?= t('site_name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= BASE_PATH ?>assets/css/app.css">
</head>

<body>
    <header class="site-header">
        <nav class="nav container">
            <a class="logo-sq" href="<?= BASE_PATH ?>">
                <img src="<?= BASE_PATH ?>assets/logo_chrysalide.png" alt="Logo Chrysalide">
            </a>

            <div class="nav-actions">
                <?php if ($isLoggedIn): ?>
                    <?php if (($_SESSION['role'] ?? null) === 'author'): ?>
                        <a class="btn ghost" href="<?= BASE_PATH ?>my_stories.php"><?= t('author_space'); ?></a>
                    <?php endif; ?>
                    <a class="btn ghost" href="<?= BASE_PATH ?>dashboard.php"><?= t('profile'); ?></a>
                    <a class="btn" href="<?= BASE_PATH ?>logout.php"><?= t('logout'); ?></a>
                <?php else: ?>
                    <a class="btn ghost" href="<?= BASE_PATH ?>login.php"><?= t('login'); ?></a>
                    <a class="btn" href="<?= BASE_PATH ?>register.php"><?= t('register'); ?></a>
                <?php endif; ?>
            </div>
        </nav>

        <?php if (!$isLoggedIn): ?>
            <div class="notice">
                <?= t('logged_out_notice'); ?>
            </div>
        <?php endif; ?>

        <div class="container" style="text-align:right; padding:.25rem 0 .75rem;">
            <span class="lang">
                <a href="?lang=fr"><?= t('lang_fr'); ?></a> |
                <a href="?lang=en"><?= t('lang_en'); ?></a>
            </span>
        </div>
    </header>

    <main class="container"></main>