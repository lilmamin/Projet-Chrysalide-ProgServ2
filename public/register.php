<?php
/**
 * Page d'inscription avec envoi d'email de confirmation
 */

$pageTitle = "Inscription";

require_once __DIR__ . '/../src/Classes/Database.php';
require_once __DIR__ . '/../src/Classes/EmailService.php';
require_once __DIR__ . '/../src/config/app.php';
require_once __DIR__ . '/../src/i18n.php';

$errors = [];
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'reader';

    // Validation
    if (empty($username)) {
        $errors[] = $lang === 'fr' ? "Le nom d'utilisateur est requis." : "Username is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $lang === 'fr' ? "Un email valide est requis." : "A valid email is required.";
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = $lang === 'fr' ? "Le mot de passe doit contenir au moins 6 caractères." : "Password must be at least 6 characters.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = $lang === 'fr' ? "Les mots de passe ne correspondent pas." : "Passwords do not match.";
    }

    // Si pas d'erreurs, insertion en base
    if (empty($errors)) {
        try {
            $database = new Database();
            $pdo = $database->getPdo();

            // Vérifier si l'email existe déjà
            $sqlCheck = "SELECT id FROM users WHERE email = :email";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindValue(':email', $email);
            $stmtCheck->execute();

            if ($stmtCheck->fetch()) {
                $errors[] = $lang === 'fr' ? "Cet email est déjà utilisé." : "This email is already in use.";
            } else {
                // Générer le token de confirmation
                $confirmationToken = bin2hex(random_bytes(32));

                // Hasher le mot de passe
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insérer l'utilisateur (note: le champ est password_hash dans ta table)
                $sql = "INSERT INTO users (username, email, password_hash, role, is_confirmed, confirmation_token, created_at) 
                        VALUES (:username, :email, :password, :role, 0, :token, NOW())";

                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':username', $username);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':password', $hashedPassword);
                $stmt->bindValue(':role', $role);
                $stmt->bindValue(':token', $confirmationToken);
                $stmt->execute();

                // Envoyer l'email de confirmation
                try {
                    $emailService = new EmailService();
                    $emailSent = $emailService->sendConfirmationEmail($email, $username, $confirmationToken, $lang);

                    if ($emailSent) {
                        $successMessage = $lang === 'fr' ?
                            "Inscription réussie ! Un email de confirmation a été envoyé à $email. Vérifiez votre boîte de réception." :
                            "Registration successful! A confirmation email has been sent to $email. Check your inbox.";
                    } else {
                        $successMessage = $lang === 'fr' ?
                            "Inscription réussie ! Cependant, l'email de confirmation n'a pas pu être envoyé. Contactez l'administrateur." :
                            "Registration successful! However, the confirmation email could not be sent. Contact the administrator.";
                    }
                } catch (Exception $e) {
                    error_log("Erreur envoi email : " . $e->getMessage());
                    $successMessage = $lang === 'fr' ?
                        "Inscription réussie ! Cependant, une erreur est survenue lors de l'envoi de l'email de confirmation." :
                        "Registration successful! However, an error occurred while sending the confirmation email.";
                }
            }

        } catch (PDOException $e) {
            $errors[] = $lang === 'fr' ? "Erreur lors de l'inscription : " . $e->getMessage() : "Registration error: " . $e->getMessage();
        }
    }
}

include __DIR__ . '/templates/header.php';
?>

<style>
    .register-container {
        max-width: 500px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-card h1 {
        color: #667eea;
        margin-bottom: 2rem;
        text-align: center;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .alert-error {
        background: #ffebee;
        color: #c62828;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #c62828;
    }

    .alert-success {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #2e7d32;
    }

    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #666;
    }

    .login-link a {
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
    }
</style>

<div class="container register-container">
    <div class="form-card">
        <h1>📝 <?= $lang === 'fr' ? 'Inscription' : 'Sign Up' ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <strong>❌ <?= $lang === 'fr' ? 'Erreurs :' : 'Errors:' ?></strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert-success">
                <strong>✓ <?= htmlspecialchars($successMessage) ?></strong>
                <p style="margin-top: 1rem;">
                    <a href="<?= BASE_PATH ?>login.php"><?= $lang === 'fr' ? 'Se connecter' : 'Log in' ?></a>
                </p>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username"><?= $lang === 'fr' ? 'Nom d\'utilisateur' : 'Username' ?> *</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password"><?= $lang === 'fr' ? 'Mot de passe' : 'Password' ?> *</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm_password"><?= $lang === 'fr' ? 'Confirmer le mot de passe' : 'Confirm Password' ?>
                        *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>

                <div class="form-group">
                    <label for="role"><?= $lang === 'fr' ? 'Rôle' : 'Role' ?> *</label>
                    <select id="role" name="role" required>
                        <option value="reader"><?= $lang === 'fr' ? 'Lecteur' : 'Reader' ?></option>
                        <option value="author"><?= $lang === 'fr' ? 'Auteur' : 'Author' ?></option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">
                    <?= $lang === 'fr' ? 'S\'inscrire' : 'Sign Up' ?>
                </button>
            </form>

            <div class="login-link">
                <?= $lang === 'fr' ? 'Déjà un compte ?' : 'Already have an account?' ?>
                <a href="<?= BASE_PATH ?>login.php"><?= $lang === 'fr' ? 'Se connecter' : 'Log in' ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/templates/footer.php'; ?>