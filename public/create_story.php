<?php
/**
 * Page de création d'histoire
 * 
 * Permet aux auteurs de créer et publier une nouvelle histoire
 * Page protégée - réservée aux utilisateurs avec le rôle "author"
 * Conforme aux bonnes pratiques vues en cours ProgServ2
 */

require_once __DIR__ . '/../src/Classes/Database.php';

// Vérification de l'authentification
require_once __DIR__ . '/auth_check.php';

// Vérification du rôle : seuls les auteurs peuvent créer des histoires
if ($_SESSION['role'] !== 'author') {
    // Redirection avec message d'erreur
    http_response_code(403);
    die('Accès refusé. Seuls les auteurs peuvent créer des histoires.');
}

// Initialisation des variables
$title = '';
$summary = '';
$content = '';
$is_published = false;
$errors = [];

// Traitement du formulaire lors de la soumission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Récupération des données du formulaire
    $title = $_POST["title"] ?? '';
    $summary = $_POST["summary"] ?? '';
    $content = $_POST["content"] ?? '';
    $is_published = isset($_POST["is_published"]) && $_POST["is_published"] === '1';
    
    // Validation côté serveur
    
    // Validation du titre
    if (empty($title)) {
        $errors[] = "Le titre est requis.";
    } elseif (strlen($title) < 3) {
        $errors[] = "Le titre doit contenir au moins 3 caractères.";
    } elseif (strlen($title) > 255) {
        $errors[] = "Le titre ne peut pas dépasser 255 caractères.";
    }
    
    // Validation du résumé
    if (empty($summary)) {
        $errors[] = "Le résumé est requis.";
    } elseif (strlen($summary) < 10) {
        $errors[] = "Le résumé doit contenir au moins 10 caractères.";
    }
    
    // Validation du contenu
    if (empty($content)) {
        $errors[] = "Le contenu de l'histoire est requis.";
    } elseif (strlen($content) < 100) {
        $errors[] = "Le contenu doit contenir au moins 100 caractères.";
    }
    
    // Si pas d'erreurs, insertion dans la base de données
    if (empty($errors)) {
        try {
            // Connexion à la base de données
            $database = new Database();
            $pdo = $database->getPdo();
            
            // Préparation de la requête SQL
            $sql = "INSERT INTO stories (
                author_id,
                title,
                summary,
                content,
                is_published,
                published_at
            ) VALUES (
                :author_id,
                :title,
                :summary,
                :content,
                :is_published,
                :published_at
            )";
            
            $stmt = $pdo->prepare($sql);
            
            // Liaison des paramètres
            $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':summary', $summary);
            $stmt->bindValue(':content', $content);
            $stmt->bindValue(':is_published', $is_published, PDO::PARAM_BOOL);
            $stmt->bindValue(':published_at', $is_published ? date('Y-m-d H:i:s') : null);
            
            // Exécution de la requête
            $stmt->execute();
            
            // Message de succès
            $successMessage = $is_published 
                ? "Histoire publiée avec succès !" 
                : "Histoire enregistrée en brouillon.";
            
            // Réinitialisation des champs du formulaire
            $title = '';
            $summary = '';
            $content = '';
            $is_published = false;
            
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
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
    <title>Créer une histoire - Chrysalide</title>
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
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #4CAF50;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
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
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }
        
        textarea {
            min-height: 300px;
            resize: vertical;
        }
        
        input:focus,
        textarea:focus {
            outline: none;
            border-color: #4CAF50;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 10px;
        }
        
        .checkbox-group label {
            font-weight: normal;
            margin: 0;
        }
        
        button {
            padding: 12px 30px;
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
        
        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .char-count {
            font-size: 12px;
            color: #666;
            text-align: right;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-link">← Retour au tableau de bord</a>
        
        <h1>Créer une nouvelle histoire</h1>
        <p class="subtitle">Partagez votre créativité avec la communauté</p>
        
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
                <br><br>
                <a href="my_stories.php">Voir mes histoires</a> ou 
                <a href="create_story.php">Créer une autre histoire</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="create_story.php">
            <div class="form-group">
                <label for="title">Titre de l'histoire *</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="<?= htmlspecialchars($title) ?>"
                    required 
                    minlength="3"
                    maxlength="255"
                >
                <p class="help-text">Entre 3 et 255 caractères</p>
            </div>
            
            <div class="form-group">
                <label for="summary">Résumé *</label>
                <textarea 
                    id="summary" 
                    name="summary" 
                    required
                    minlength="10"
                    style="min-height: 100px;"
                ><?= htmlspecialchars($summary) ?></textarea>
                <p class="help-text">Résumé accrocheur de votre histoire (minimum 10 caractères)</p>
            </div>
            
            <div class="form-group">
                <label for="content">Contenu de l'histoire *</label>
                <textarea 
                    id="content" 
                    name="content" 
                    required
                    minlength="100"
                ><?= htmlspecialchars($content) ?></textarea>
                <p class="help-text">Le contenu complet de votre histoire (minimum 100 caractères)</p>
            </div>
            
            <div class="checkbox-group">
                <input 
                    type="checkbox" 
                    id="is_published" 
                    name="is_published" 
                    value="1"
                    <?= $is_published ? 'checked' : '' ?>
                >
                <label for="is_published">
                    Publier immédiatement (sinon, enregistrer en brouillon)
                </label>
            </div>
            
            <div style="margin-top: 30px;">
                <button type="submit">Enregistrer l'histoire</button>
            </div>
        </form>
    </div>
</body>
</html>