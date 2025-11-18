<?php
require_once __DIR__ . '/../src/auth.php';
//require_once __DIR__ . '/../src/i18n.php';

$msg = $err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = in_array($_POST['role'] ?? 'reader', ['reader', 'author']) ? $_POST['role'] : 'reader';
    if (register($_POST['username'] ?? '', $_POST['email'] ?? '', $_POST['password'] ?? '', $role)) {
        $msg = "Compte créé. Tu peux te connecter.";
    } else {
        $err = "Impossible de créer le compte.";
    }
}

include __DIR__ . '/../templates/header.php'; ?>
<h1><?= t('register'); ?></h1>
<?php if ($msg)
    echo "<p class='ok'>" . htmlspecialchars($msg) . "</p>";
if ($err)
    echo "<p class='err'>" . htmlspecialchars($err) . "</p>"; ?>
<form method="post" class="form">
    <input name="username" type="username" required placeholder="username">
    <input name="email" type="email" required placeholder="email">
    <input name="password" type="password" required placeholder="password">
    <label><input type="radio" name="role" value="reader" checked> Lecteur·ice</label>
    <label><input type="radio" name="role" value="author"> Auteur·ice</label>
    <button type="submit"><?= t('register'); ?></button>
</form>
<?php include __DIR__ . '/templates/footer.php';
