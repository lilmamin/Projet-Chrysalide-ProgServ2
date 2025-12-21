<?php
/**
 * Page de confirmation d'email
 */

$pageTitle = "Confirmation de compte";

require_once __DIR__ . '/../src/Classes/Database.php';
require_once __DIR__ . '/../src/config/app.php';

$message = '';
$success = false;

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    try {
        $database = new Database();
        $pdo = $database->getPdo();

        // Chercher l'utilisateur avec ce token
        $sql = "SELECT id, username, is_confirmed FROM users WHERE confirmation_token = :token";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user) {
            if ($user['is_confirmed']) {
                // Déjà confirmé
                $message = $lang === 'fr' ?
                    "Votre compte a déjà été confirmé. Vous pouvez vous connecter." :
                    "Your account has already been confirmed. You can log in.";
                $success = true;
            } else {
                // Confirmer le compte
                $sqlUpdate = "UPDATE users 
                             SET is_confirmed = 1, 
                                 confirmed_at = NOW(), 
                                 confirmation_token = NULL 
                             WHERE id = :id";
                $stmtUpdate = $pdo->prepare($sqlUpdate);
                $stmtUpdate->bindValue(':id', $user['id'], PDO::PARAM_INT);
                $stmtUpdate->execute();

                $message = $lang === 'fr' ?
                    "Félicitations " . htmlspecialchars($user['username']) . " ! Votre compte a été confirmé avec succès. Vous pouvez maintenant vous connecter." :
                    "Congratulations " . htmlspecialchars($user['username']) . "! Your account has been confirmed successfully. You can now log in.";
                $success = true;
            }
        } else {
            $message = $lang === 'fr' ?
                "Lien de confirmation invalide ou expiré." :
                "Invalid or expired confirmation link.";
        }

    } catch (PDOException $e) {
        $message = $lang === 'fr' ?
            "Une erreur est survenue lors de la confirmation : " . $e->getMessage() :
            "An error occurred during confirmation: " . $e->getMessage();
    }
} else {
    $message = $lang === 'fr' ?
        "Aucun token de confirmation fourni." :
        "No confirmation token provided.";
}

include __DIR__ . '/templates/header.php';
?>

<style>
    .confirm-container {
        max-width: 600px;
        margin: 4rem auto;
        text-align: center;
    }

    .confirm-card {
        background: white;
        padding: 3rem;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .icon-success {
        font-size: 4rem;
        margin-bottom: 1.5rem;
    }

    .icon-error {
        font-size: 4rem;
        margin-bottom: 1.5rem;
    }

    .confirm-card h1 {
        color: #667eea;
        margin-bottom: 1.5rem;
    }

    .confirm-message {
        font-size: 1.1rem;
        color: #333;
        line-height: 1.8;
        margin-bottom: 2rem;
    }

    .btn-login {
        display: inline-block;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-home {
        display: inline-block;
        padding: 1rem 2rem;
        background: #f5f5f5;
        color: #667eea;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        margin-top: 1rem;
    }

    .btn-home:hover {
        background: #e0e0e0;
    }
</style>

<div class="container confirm-container">
    <div class="confirm-card">
        <?php if ($success): ?>
            <div class="icon-success">✅</div>
            <h1><?= $lang === 'fr' ? 'Confirmation réussie !' : 'Confirmation Successful!' ?></h1>
        <?php else: ?>
            <div class="icon-error">❌</div>
            <h1><?= $lang === 'fr' ? 'Erreur de confirmation' : 'Confirmation Error' ?></h1>
        <?php endif; ?>

        <p class="confirm-message"><?= $message ?></p>

        <?php if ($success): ?>
            <a href="<?= BASE_PATH ?>login.php" class="btn-login">
                <?= $lang === 'fr' ? '🔓 Se connecter' : '🔓 Log In' ?>
            </a>
        <?php endif; ?>

        <br>
        <a href="<?= BASE_PATH ?>" class="btn-home">
            <?= $lang === 'fr' ? '🏠 Retour à l\'accueil' : '🏠 Back to Home' ?>
        </a>
    </div>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>