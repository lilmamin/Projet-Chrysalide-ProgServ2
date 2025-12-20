<?php
/**
 * Page de modification d'histoire
 * 
 * Permet aux auteurs de modifier une de leurs histoires existantes
 * Page protégée - réservée aux utilisateurs avec le rôle "author"
 * Sécurité : un auteur ne peut modifier que ses propres histoires
 */

require_once __DIR__ . '/../src/Classes/Database.php';

// Vérification de l'authentification
require_once __DIR__ . '/auth_check.php';

// Vérification du rôle : seuls les auteurs peuvent modifier des histoires
if ($_SESSION['role'] !== 'author') {
    http_response_code(403);
    die('Accès refusé. Seuls les auteurs peuvent modifier des histoires.');
}

// Vérification de la présence de l'ID de l'histoire
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: my_stories.php');
    exit();
}

$storyId = (int) $_GET['id'];
$errors = [];

// Connexion à la base de données
try {
    $database = new Database();
    $pdo = $database->getPdo();
    
    // Récupération de l'histoire (seulement si elle appartient à l'auteur connecté)
    $sql = "SELECT * FROM stories WHERE id = :id AND author_id = :author_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $storyId, PDO::PARAM_INT);
    $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $story = $stmt->fetch();
    
    // Si l'histoire n'existe pas ou n'appartient pas à l'auteur
    if (!$story) {
        header('Location: my_stories.php');
        exit();
    }
    
} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'histoire : " . $e->getMessage());
}

// Initialisation des variables avec les valeurs actuelles
$title = $story['title'];
$summary = $story['summary'];
$content = $story['content'];
$is_published = (bool) $story['is_published'];

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
    
    // Si pas d'erreurs, mise à jour dans la base de données
    if (empty($errors)) {
        try {
            // Déterminer si c'est la première publication
            $wasPublished = (bool) $story['is_published'];
            $publishedAt = null;
            
            if ($is_published && !$wasPublished) {
                // Première publication : on définit la date
                $publishedAt = date('Y-m-d H:i:s');
            } elseif ($is_published && $wasPublished) {
                // Déjà publié : on garde la date originale
                $publishedAt = $story['published_at'];
            }
            // Si dépublié : $publishedAt reste null
            
            // Préparation de la requête SQL
            $sql = "UPDATE stories SET
                title = :title,
                summary = :summary,
                content = :content,
                is_published = :is_published,
                published_at = :published_at,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND author_id = :author_id";
            
            $stmt = $pdo->prepare($sql);
            
            // Liaison des paramètres
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':summary', $summary);
            $stmt->bindValue(':content', $content);
            $stmt->bindValue(':is_published', $is_published, PDO::PARAM_BOOL);
            $stmt->bindValue(':published_at', $publishedAt);
            $stmt->bindValue(':id', $storyId, PDO::PARAM_INT);
            $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
            
            // Exécution de la requête
            $stmt->execute();
            
            // Message de succès
            $successMessage = "Histoire modifiée avec succès !";
            
            // Rafraîchir les données de l'histoire
            $story['title'] = $title;
            $story['summary'] = $summary;
            $story['content'] = $content;
            $story['is_published'] = $is_published;
            
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la modification : " . $e->getMessage();
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
    <title>Modifier l'histoire - Chrysalide</title>
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
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        
        .status-published {
            background-color: #4CAF50;
            color: white;
        }
        
        .status-draft {
            background-color: #FF9800;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="my_stories.php" class="back-link">← Retour à mes histoires</a>
        
        <h1>
            Modifier l'histoire
            <span class="status-badge <?= $story['is_published'] ? 'status-published' : 'status-draft' ?>">
                <?= $story['is_published'] ? 'Publiée' : 'Brouillon' ?>
            </span>
        </h1>
        <p class="subtitle">Modifiez votre histoire et enregistrez les changements</p>
        
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
                <a href="my_stories.php">Retour à mes histoires</a> ou 
                <a href="read_story.php?id=<?= $storyId ?>">Voir l'histoire</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="edit_story.php?id=<?= $storyId ?>">
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
                    Publier cette histoire (visible par tous les lecteurs)
                </label>
            </div>
            
            <div style="margin-top: 30px;">
                <button type="submit">Enregistrer les modifications</button>
            </div>
        </form>
    </div>
</body>
</html>