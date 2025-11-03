<?php if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once __DIR__ . '/../../src/i18n.php';
require_once __DIR__ . '/../../src/config/app.php'; ?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title><?= t('site_name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= BASE_PATH ?>assets/css/app.css">
</head>

<body>
    <header class="site-header">
        <nav class="nav">
            <a class="brand" href="/"><?= t('site_name'); ?></a>
            <a href="/discover.php"><?= t('discover'); ?></a>
            <?php if (!empty($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] === 'author'): ?>
                    <a href="/author/my-stories.php"><?= t('author_space'); ?></a>
                <?php endif; ?>
                <a href="/profile.php"><?= t('profile'); ?></a>
                <a href="/login.php?logout=1"><?= t('logout'); ?></a>
            <?php else: ?>
                <a href="/login.php"><?= t('login'); ?></a>
                <a href="/register.php"><?= t('register'); ?></a>
            <?php endif; ?>
            <span class="lang">
                <a href="?lang=fr"><?= t('lang_fr'); ?></a> | <a href="?lang=en"><?= t('lang_en'); ?></a>
            </span>
        </nav>
    </header>
    <main class="container">