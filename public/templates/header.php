<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . '/../../src/i18n.php';
require_once __DIR__ . '/../../src/config/app.php';

$base = rtrim(BASE_PATH ?? '/', '/') . '/';
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang) ?>">

<head>
    <meta charset="utf-8">
    <title><?= t('site_name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= $base ?>assets/css/app.css">
</head>

<body>
    <header class="site-header">
        <nav class="nav container">
            <a class="brand" href="<?= $base ?>">
                <span class="logo-sq" aria-hidden="true">logoooo</span>
                <span class="brand-text"><?= t('site_name'); ?></span>
            </a>

            <div class="nav-actions">
                <?php if (!empty($_SESSION['user'])): ?>
                    <?php if (($_SESSION['user']['role'] ?? null) === 'author'): ?>
                        <a class="btn ghost" href="<?= $base ?>author/my-stories.php"><?= t('author_space'); ?></a>
                    <?php endif; ?>
                    <a class="btn ghost" href="<?= $base ?>profile.php"><?= t('profile'); ?></a>
                    <a class="btn" href="<?= $base ?>login.php?logout=1"><?= t('logout'); ?></a>
                <?php else: ?>
                    <a class="btn ghost" href="<?= $base ?>login.php"><?= t('login'); ?></a>
                    <a class="btn" href="<?= $base ?>register.php"><?= t('register'); ?></a>
                <?php endif; ?>
            </div>
        </nav>

        <div class="notice">
            <?= t('logged_out_notice'); ?>
        </div>

        <div class="container" style="text-align:right; padding:.25rem 0 .75rem;">
            <span class="lang">
                <a href="?lang=fr"><?= t('lang_fr'); ?></a> |
                <a href="?lang=en"><?= t('lang_en'); ?></a>
            </span>
        </div>
    </header>

    <main class="container">