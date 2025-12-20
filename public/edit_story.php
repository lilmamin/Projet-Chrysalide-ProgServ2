<?php
/**
 * Page de modification d'histoire
 */

$pageTitle = "Modifier l'histoire";

require_once __DIR__ . '/../src/Classes/Database.php';
require_once __DIR__ . '/../src/config/app.php';
require_once __DIR__ . '/auth_check.php';

// Vérification du rôle
if ($_SESSION['role'] !== 'author') {
    http_response_code(403);
    die('Accès refusé. Seuls les auteurs peuvent modifier des histoires.');
}

// Vérification de la présence de l'ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ' . BASE_PATH . 'my_stories.php');
    exit();
}

$storyId = (int) $_GET['id'];
$errors = [];

// Connexion et récupération de l'histoire
try {
    $database = new Database();
    $pdo = $database->getPdo();
    
    $sql = "SELECT * FROM stories WHERE id = :id AND author_id = :author_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $storyId, PDO::PARAM_INT);
    $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $story = $stmt->fetch();
    
    if (!$story) {
        header('Location: ' . BASE_PATH . 'my_stories.php');
        exit();
    }
    
} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'histoire : " . $e->getMessage());
}

// Initialisation des variables
$title = $story['title'];
$summary = $story['summary'];
$content = $story['content'];
$is_published = (bool) $story['is_published'];

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
    
    // Mise à jour si pas d'erreurs
    if (empty($errors)) {
        try {
            $wasPublished = (bool) $story['is_published'];
            $publishedAt = null;
            
            if ($is_published && !$wasPublished) {
                $publishedAt = date('Y-m-d H:i:s');
            } elseif ($is_published && $wasPublished) {
                $publishedAt = $story['published_at'];
            }
            
            $sql = "UPDATE stories SET
                title = :title,
                summary = :summary,
                content = :content,
                is_published = :is_published,
                published_at = :published_at,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND author_id = :author_id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $title);
            $stmt->bindValue(':summary', $summary);
            $stmt->bindValue(':content', $content);
            $stmt->bindValue(':is_published', $is_published, PDO::PARAM_BOOL);
            $stmt->bindValue(':published_at', $publishedAt);
            $stmt->bindValue(':id', $storyId, PDO::PARAM_INT);
            $stmt->bindValue(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $successMessage = "Histoire modifiée avec succès !";
            
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

include __DIR__ . '/templates/header.php';
?>

<style>
    .page-header-edit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .page-header-edit h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .page-header-edit .subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin: 0;
    }

    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .form-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-group textarea {
        resize: vertical;
        line-height: 1.6;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .help-text {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.3rem;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin: 1.5rem 0;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .checkbox-group label {
        margin: 0;
        cursor: pointer;
        font-weight: normal;
        color: #333;
    }

    .btn-submit {
        display: inline-block;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
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

    .alert-error {
        background: #ffebee;
        color: #c62828;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #c62828;
    }

    .alert-error ul {
        margin: 0.5rem 0 0 1.5rem;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #2e7d32;
    }

    .alert-success a {
        color: #1b5e20;
        font-weight: 600;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .back-link:hover {
        gap: 0.8rem;
    }

    @media (max-width: 768px) {
        .page-header-edit h1 {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .form-card {
            padding: 1.5rem;
        }

        .btn-submit {
            width: 100%;
        }
    }
</style>

<div class="container">
    <a href="<?= BASE_PATH ?>my_stories.php" class="back-link">← Retour à mes histoires</a>

    <div class="page-header-edit">
        <h1>
            ✏️ Modifier l'histoire
            <span class="status-badge">
                <?= $story['is_published'] ? '✓ Publiée' : '📝 Brouillon' ?>
            </span>
        </h1>
        <p class="subtitle">Modifiez votre histoire et enregistrez les changements</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert-error">
            <strong>❌ Erreurs :</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (isset($successMessage)): ?>
        <div class="alert-success">
            <strong>✓ <?= htmlspecialchars($successMessage) ?></strong><br><br>
            <a href="<?= BASE_PATH ?>my_stories.php">← Retour à mes histoires</a> ou 
            <a href="<?= BASE_PATH ?>read_story.php?id=<?= $storyId ?>">👁 Voir l'histoire</a>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="<?= BASE_PATH ?>edit_story.php?id=<?= $storyId ?>">
            <div class="form-group">
                <label for="title">📝 Titre de l'histoire *</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="<?= htmlspecialchars($title) ?>"
                    required 
                    minlength="3"
                    maxlength="255"
                    placeholder="Un titre accrocheur..."
                >
                <p class="help-text">Entre 3 et 255 caractères</p>
            </div>

            <div class="form-group">
                <label for="summary">📄 Résumé *</label>
                <textarea 
                    id="summary" 
                    name="summary" 
                    required
                    minlength="10"
                    style="min-height: 120px;"
                    placeholder="Un résumé captivant qui donnera envie de lire votre histoire..."
                ><?= htmlspecialchars($summary) ?></textarea>
                <p class="help-text">Minimum 10 caractères - Résumé accrocheur de votre histoire</p>
            </div>

            <div class="form-group">
                <label for="content">📖 Contenu de l'histoire *</label>
                <textarea 
                    id="content" 
                    name="content" 
                    required
                    minlength="100"
                    style="min-height: 400px;"
                    placeholder="Il était une fois..."
                ><?= htmlspecialchars($content) ?></textarea>
                <p class="help-text">Minimum 100 caractères - Le contenu complet de votre histoire</p>
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
                    <strong>📢 Publier cette histoire</strong> (visible par tous les lecteurs)
                </label>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn-submit">
                    💾 Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>