<?php
require_once __DIR__ . '/../../src/config/app.php';
require_once __DIR__ . '/../../src/i18n.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$isAuthor = $isLoggedIn && ($_SESSION['role'] ?? null) === 'author';
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang ?? 'fr') ?>">

<head>
    <meta charset="utf-8">
    <title><?= $pageTitle ?? 'Chrysalide' ?> - Chrysalide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= BASE_PATH ?>assets/css/app.css">
    <style>
        /* Styles globaux pour harmoniser le design */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header amélioré */
        .site-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-top {
            background: rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
        }

        .header-top .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .lang-switcher a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            margin: 0 5px;
        }

        .lang-switcher a:hover {
            color: white;
        }

        .header-main {
            padding: 1rem 0;
        }

        .header-main .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: white;
        }

        .logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .logo-text {
            font-size: 2.2rem;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .nav-main {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-main a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-main a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-main .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
        }

        .nav-main .btn-primary:hover {
            background: white;
            color: #667eea;
        }

        .user-info-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50px;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: white;
            color: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            width: 100%;
        }

        main {
            flex: 1;
            padding: 2rem 0;
        }

        /* Utilitaires */
        .text-center {
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-main .container {
                flex-direction: column;
                text-align: center;
            }

            .nav-main {
                flex-direction: column;
                width: 100%;
            }

            .nav-main a {
                width: 100%;
                text-align: center;
            }

            .logo-text {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>
    <header class="site-header">
        <!-- Barre du haut avec langue -->
        <div class="header-top">
            <div class="container">
                <div></div>
                <div class="lang-switcher">
                    <a href="?lang=fr">🇫🇷 Français</a>
                    <span style="color: rgba(255,255,255,0.5);">|</span>
                    <a href="?lang=en">🇬🇧 English</a>
                </div>
            </div>
        </div>

        <!-- Barre principale avec logo et navigation -->
        <div class="header-main">
            <div class="container">
                <!-- Logo -->
                <a href="<?= BASE_PATH ?>" class="logo">
                    <img src="<?= BASE_PATH ?>assets/logo_chrysalide.png" alt="Logo Chrysalide">
                </a>

                <!-- Navigation -->
                <nav class="nav-main">
                    <a href="<?= BASE_PATH ?>"><?= t('discover') ?></a>

                    <?php if ($isLoggedIn): ?>
                        <?php if ($isAuthor): ?>
                            <a href="<?= BASE_PATH ?>my_stories.php">Mes histoires</a>
                            <a href="<?= BASE_PATH ?>create_story.php">Nouvelle histoire</a>
                        <?php endif; ?>

                        <a href="<?= BASE_PATH ?>dashboard.php">Mon dashboard</a>

                        <div class="user-info-header">
                            <div class="user-avatar">
                                <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
                            </div>
                            <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                        </div>

                        <a href="<?= BASE_PATH ?>logout.php" class="btn-primary"><?= t('logout') ?></a>
                    <?php else: ?>
                        <a href="<?= BASE_PATH ?>login.php"><?= t('login') ?></a>
                        <a href="<?= BASE_PATH ?>register.php" class="btn-primary"><?= t('register') ?></a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <main>