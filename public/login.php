<?php
require_once __DIR__ . '/../src/auth.php';
//require_once __DIR__ . '/../src/i18n.php';

if (isset($_GET['logout'])) {
    logout();
    header('Location: /');
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['username'] ?? '', $_POST['email'] ?? '', $_POST['password'] ?? '')) {
        header('Location: /');
        exit;
    } else {
        $error = "Email ou mot de passe invalide.";
    }
}

include __DIR__ . '/../templates/header.php'; ?>
<h1><?= t('login'); ?></h1>
<?php if ($error)
    echo "<p class='err'>" . htmlspecialchars($error) . "</p>"; ?>
<form method="post" class="form">
    <input name="username" type="username" required placeholder="username">
    <input name="email" type="email" required placeholder="email">
    <label for="role-selection">Veuillez sélectionner un rôle:</label>
    <select name="roles" id="role-selection">
        <option value="">Rôles</option>
        <option value="author">Auteur·ice</option>
        <option value="reader">Lecteur</option>
    </select>
    <input name="password" type="password" required placeholder="mot de passe">
    <button type="submit"><?= t('login'); ?></button>
</form>
<p><a href="/register.php"><?= t('register'); ?></a></p>
<?php include __DIR__ . '/templates/footer.php';
