<?php
/**
 * Page d'inscription (Register)
 * 
 * Permet à un utilisateur de créer un compte sur la plateforme Chrysalide
 * Conforme aux bonnes pratiques vues en cours ProgServ2
 */

require_once __DIR__ . '/../src/Classes/Database.php';

// Initialisation des variables
$username = '';
$email = '';
$role = 'reader'; // Rôle par défaut
$errors = [];

// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données du formulaire
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    $passwordConfirm = $_POST["password_confirm"] ?? '';
    $role = $_POST["role"] ?? 'reader';

    // Validation côté serveur

    // Validation du nom d'utilisateur
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
    } elseif (strlen($username) > 50) {
        $errors[] = "Le nom d'utilisateur ne peut pas dépasser 50 caractères.";
    }

    // Validation de l'email
    if (empty($email)) {
        $errors[] = "L'adresse email est requise.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }

    // Validation du mot de passe
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    }

    // Validation de la confirmation du mot de passe
    if ($password !== $passwordConfirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Validation du rôle
    if (!in_array($role, ['reader', 'author'])) {
        $errors[] = "Le rôle sélectionné n'est pas valide.";
    }

    // Si pas d'erreurs, insertion dans la base de données
    if (empty($errors)) {
        try {
            // Connexion à la base de données
            $database = new Database();
            $pdo = $database->getPdo();

            // Hashage sécurisé du mot de passe
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Génération d'un token de confirmation unique
            $confirmationToken = bin2hex(random_bytes(32));

            // Préparation de la requête SQL
            $sql = "INSERT INTO users (
                username,
                email,
                password_hash,
                role,
                is_confirmed,
                confirmation_token
            ) VALUES (
                :username,
                :email,
                :password_hash,
                :role,
                :is_confirmed,
                :confirmation_token
            )";

            $stmt = $pdo->prepare($sql);

            // Liaison des paramètres
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':password_hash', $passwordHash);
            $stmt->bindValue(':role', $role);
            $stmt->bindValue(':is_confirmed', false, PDO::PARAM_BOOL);
            $stmt->bindValue(':confirmation_token', $confirmationToken);

            // Exécution de la requête
            $stmt->execute();

            // Message de succès (en production, on enverrait un email ici)
            $successMessage = "Compte créé avec succès ! Un email de confirmation a été envoyé à votre adresse.";

            // Réinitialisation des champs du formulaire
            $username = '';
            $email = '';
            $role = 'reader';

        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            // Code 23000 = violation de contrainte (email ou username déjà utilisé)
            if ($e->getCode() === "23000") {
                $errors[] = "Cette adresse email ou ce nom d'utilisateur est déjà utilisé.";
            } else {
                $errors[] = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        } catch (Exception $e) {
            $errors[] = "Erreur inattendue : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Chrysalide</title>
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
            max-width: 500px;
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

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #4CAF50;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-box {
            background-color: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #c62828;
        }

        .error-box ul {
            margin-left: 20px;
        }

        .success-box {
            background-color: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border-left: 4px solid #2e7d32;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Inscription</h1>
        <p class="subtitle">Créez votre compte sur Chrysalide</p>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <strong>Erreurs :</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <div class="success-box">
                <?= htmlspecialchars($successMessage) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Nom d'utilisateur *</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>" required
                    minlength="3" maxlength="50">
                <p class="help-text">Entre 3 et 50 caractères</p>
            </div>

            <div class="form-group">
                <label for="email">Adresse email *</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe *</label>
                <input type="password" id="password" name="password" required minlength="8">
                <p class="help-text">Au moins 8 caractères</p>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmer le mot de passe *</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="8">
            </div>

            <div class="form-group">
                <label for="role">Je souhaite m'inscrire en tant que *</label>
                <select id="role" name="role" required>
                    <option value="reader" <?= $role === 'reader' ? 'selected' : '' ?>>
                        Lecteur (lire des histoires)
                    </option>
                    <option value="author" <?= $role === 'author' ? 'selected' : '' ?>>
                        Auteur (écrire et publier des histoires)
                    </option>
                </select>
            </div>

            <button type="submit">Créer mon compte</button>
        </form>

        <div class="login-link">
            Vous avez déjà un compte ? <a href="login.php">Se connecter</a>
        </div>
    </div>
</body>

</html>