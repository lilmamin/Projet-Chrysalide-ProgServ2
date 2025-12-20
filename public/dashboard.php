<?php
/**
 * Page d'accueil (dashboard privé)
 * 
 * Page protégée accessible uniquement aux utilisateurs authentifiés
 * Affiche les informations de l'utilisateur connecté
 */

// Vérification de l'authentification
require_once __DIR__ . '/auth_check.php';

// À ce stade, l'utilisateur est forcément authentifié
// Ses informations sont disponibles dans $_SESSION
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Chrysalide</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .user-info {
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #4CAF50;
        }

        .user-info p {
            margin: 5px 0;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }

        .role-reader {
            background-color: #2196F3;
            color: white;
        }

        .role-author {
            background-color: #FF9800;
            color: white;
        }

        .actions {
            margin: 30px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-right: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .btn-logout {
            background-color: #f44336;
        }

        .btn-logout:hover {
            background-color: #da190b;
        }

        .welcome {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="welcome">
            <h1>Bienvenue sur Chrysalide, <?= htmlspecialchars($_SESSION['username']) ?> !</h1>
        </div>

        <div class="user-info">
            <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($_SESSION['email']) ?></p>
            <p>
                <strong>Rôle :</strong>
                <?php if ($_SESSION['role'] === 'author'): ?>
                    Auteur <span class="role-badge role-author">AUTEUR</span>
                <?php else: ?>
                    Lecteur <span class="role-badge role-reader">LECTEUR</span>
                <?php endif; ?>
            </p>
            <p>
                <strong>Statut :</strong>
                <?php if ($_SESSION['is_confirmed']): ?>
                    ✓ Compte confirmé
                <?php else: ?>
                    ⚠ En attente de confirmation
                <?php endif; ?>
            </p>
        </div>

        <div class="actions">
            <h2>Actions disponibles</h2>
            <p style="margin: 20px 0;">
                <?php if ($_SESSION['role'] === 'author'): ?>
                    <a href="stories.php" class="btn">Mes histoires</a>
                    <a href="create_story.php" class="btn">Écrire une histoire</a>
                <?php else: ?>
                    <a href="stories.php" class="btn">Parcourir les histoires</a>
                <?php endif; ?>

                <a href="logout.php" class="btn btn-logout">Se déconnecter</a>
            </p>
        </div>

        <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">

        <div>
            <h2>À propos de Chrysalide</h2>
            <p style="color: #666; line-height: 1.6; margin-top: 10px;">
                Chrysalide est une plateforme de lecture et d'écriture d'histoires.
                Les lecteurs peuvent découvrir et lire des histoires créées par des auteurs talentueux.
                Les auteurs peuvent partager leurs créations avec la communauté.
            </p>
        </div>
    </div>
</body>

</html>