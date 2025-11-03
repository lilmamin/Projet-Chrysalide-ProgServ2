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
    if (login_fake($_POST['email'] ?? '', $_POST['password'] ?? '')) {
        header('Location: /');
        exit;
    } else {
        $error = "Email ou mot de passe invalide (essayez author@test.dev / secret).";
    }
}

include __DIR__ . '/../templates/header.php'; ?>
<h1><?= t('login'); ?></h1>
<?php if ($error)
    echo "<p class='err'>" . htmlspecialchars($error) . "</p>"; ?>
<form method="post" class="form">
    <input name="email" type="email" required placeholder="email">
    <input name="password" type="password" required placeholder="mot de passe">
    <button type="submit"><?= t('login'); ?></button>
</form>
<p><a href="/register.php"><?= t('register'); ?></a></p>
<?php include __DIR__ . '/templates/footer.php';
