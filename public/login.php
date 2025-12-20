<?php
/**
 * Page de connexion (Login)
 * 
 * Permet à un utilisateur de se connecter à la plateforme Chrysalide
 * Utilise les sessions pour maintenir l'état de connexion
 * Conforme aux bonnes pratiques vues en cours ProgServ2
 */

require_once __DIR__ . '/../src/Classes/Database.php';

// Initialisation des variables
$email = '';
$errors = [];

// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données du formulaire
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    // Validation côté serveur
    if (empty($email)) {
        $errors[] = "L'adresse email est requise.";
    }

    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }

    // Si pas d'erreurs de validation, tentative de connexion
    if (empty($errors)) {
        try {
            // Connexion à la base de données
            $database = new Database();
            $pdo = $database->getPdo();

            // Récupération de l'utilisateur par son email
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch();

            // Vérification du mot de passe
            if ($user && password_verify($password, $user['password_hash'])) {
                // Authentification réussie

                // Démarrage de la session
                session_start();

                // Stockage des informations utilisateur dans la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_confirmed'] = $user['is_confirmed'];

                // Redirection vers le tableau de bord
                header('Location: dashboard.php');
                exit();

            } else {
                // Authentification échouée
                $errors[] = "Email ou mot de passe incorrect.";
            }

        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la connexion : " . $e->getMessage();
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
    <title>Connexion - Chrysalide</title>
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

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        input:focus {
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

        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .register-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Connexion</h1>
        <p class="subtitle">Connectez-vous à votre compte Chrysalide</p>

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

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Se connecter</button>
        </form>

        <div class="register-link">
            Pas encore de compte ? <a href="register.php">S'inscrire</a>
        </div>
    </div>
</body>

</html>