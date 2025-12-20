<?php
/**
 * Page de création d'histoire
 */

$pageTitle = "Créer une histoire";

require_once __DIR__ . '/../src/Classes/Database.php';
require_once __DIR__ . '/../src/config/app.php';
require_once __DIR__ . '/auth_check.php';

// Vérification du rôle
if ($_SESSION['role'] !== 'author') {
    http_response_code(403);
    die('Accès refusé. Seuls les auteurs peuvent créer des histoires.');
}

// Initialisation des variables
$title = '';
$summary = '';
$content = '';
$is_published = false;
$errors = [];

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $title = $_POST["title"] ?? '';
    $summary = $_POST["summary"] ?? '';
    $content = $_POST["content"] ?? '';
    $is_published = isset($_POST["is_published"]) && $_POST["is_published"] === '1';
    
    // Validation
    if (empty($title)) {
        $errors[] = "Le titre est requis.";
    } elseif (strlen($title) < 3) {
        $errors[] = "Le titre doit contenir au moins 3 caractères.";
    } elseif (strlen($title) > 255) {
        $errors[] = "Le titre ne peut pas dépasser 255 caractères.";
    }
    
    if (empty($summary)) {
        $errors[] = "Le résumé est requis.";
    } elseif (strlen($summary) < 10) {
        $errors[] = "Le résumé doit contenir au moins 10 caractères.";
    }
    
    if (empty($content)) {
        $errors[] = "Le contenu de l'histoire est requis.";
    } elseif (strlen($content) < 100) {
        $errors[] = "Le contenu doit contenir au moins 100 caractères.";
    }
    
    // Insertion si pas d'erreurs
    if (empty($errors)) {
        try {
            $database = new Database();
            $pdo = $database->getPdo();
            
            $sql = "INSERT INTO stories (
                author_id, title, summary, content, is_published, published_at
            ) VALUES (
                :author_id, :title, :summary, :content, :is_published, :published_at
            )";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':summary', $summary);
            $stmt->bindValue(':content', $content);
            $stmt->bindValue(':is_published', $is_published, PDO::PARAM_BOOL);
            $stmt->bindValue(':published_at', $is_published ? date('Y-m-d H:i:s') : null);
            
            $stmt->execute();
            
            $successMessage = $is_published 
                ? "Histoire publiée avec succès !" 
                : "Histoire enregistrée en brouillon.";
            
            $title = '';
            $summary = '';
            $content = '';
            $is_published = false;
            
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        }
    }
}

include __DIR__ . '/templates/header.php';
?>

<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.3s;
    }

    .back-link:hover {
        gap: 0.8rem;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .page-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        opacity: 0.95;
    }

    .form-card {
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-group {
        margin-bottom: 1.8rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.6rem;
        color: #333;
        font-weight: 600;
        font-size: 1rem;
    }

    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 0.9rem 1.1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        line-height: 1.7;
    }

    .textarea-small {
        min-height: 100px;
    }

    .textarea-large {
        min-height: 350px;
    }

    .help-text {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.4rem;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin: 2rem 0;
        padding: 1.2rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 22px;
        height: 22px;
        cursor: pointer;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
        font-weight: 500;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .alert {
        padding: 1.2rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border-left: 4px solid;
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        border-left-color: #c62828;
    }

    .alert-error ul {
        margin: 0.5rem 0 0 1.5rem;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        border-left-color: #2e7d32;
    }

    .alert-success a {
        color: #1b5e20;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 1.5rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container form-container">
    
    <div class="page-header">
        <h1>Créer une nouvelle histoire</h1>
        <p>Partagez votre créativité avec la communauté Chrysalide</p>
    </div>
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>⚠️ Erreurs :</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success">
            <strong>✓ <?= htmlspecialchars($successMessage) ?></strong>
            <br><br>
            <a href="<?= BASE_PATH ?>my_stories.php">Voir mes histoires</a> ou 
            <a href="<?= BASE_PATH ?>create_story.php">Créer une autre histoire</a>
        </div>
    <?php endif; ?>
    
    <div class="form-card">
        <form method="POST" action="<?= BASE_PATH ?>create_story.php">
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
                    placeholder="Ex: Le voyage extraordinaire"
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
                    class="textarea-small"
                    placeholder="Un résumé accrocheur qui donnera envie de lire votre histoire..."
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
                    class="textarea-large"
                    placeholder="Il était une fois..."
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
                    Publier immédiatement (sinon elle sera enregistrée comme brouillon !)
                </label>
            </div>
            
            <button type="submit" class="btn-submit">💾 Enregistrer l'histoire</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>