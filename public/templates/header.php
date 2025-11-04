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
            <header class="site-header">
                <nav class="nav container">
                    <a class="brand" href="#">
                        <span class="logo-sq" aria-hidden="true">logoooo</span>
                        <span class="brand-text">Chrysalide</span>
                    </a>

                    <div class="nav-actions">
                        <a class="btn ghost" href="#">Se connecter</a>
                        <a class="btn" href="#">S’inscrire</a>
                    </div>
                </nav>

                <div class="notice">
                    Vous êtes déconnecté·e ! Connectez-vous pour lire les œuvres !
                </div>
            </header>
            <span class="lang">
                <a href="?lang=fr"><?= t('lang_fr'); ?></a> | <a href="?lang=en"><?= t('lang_en'); ?></a>
            </span>
        </nav>
    </header>
    <main class="container">