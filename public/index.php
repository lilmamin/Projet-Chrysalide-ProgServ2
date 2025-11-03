<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">

    <title>Créer un.e nouvel.le utilisateur.trice | MyApp</title>
</head>

<body>
    <main class="container">
        <h1>Créer un.e nouvel.le utilisateur.trice</h1>

        <?php if ($_SERVER["REQUEST_METHOD"] === "POST") { ?>
            <?php if (empty($errors)) { ?>
                <p style="color: green;">Le formulaire a été soumis avec succès !</p>
            <?php } else { ?>
                <p style="color: red;">Le formulaire contient des erreurs :</p>
                <ul>
                    <?php foreach ($errors as $error) { ?>
                        <li><?php echo $error; ?></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        <?php } ?>

        <form action="create.php" method="POST">
            <label for="first-name">Prénom</label>
            <input type="text" id="first-name" name="first-name" value="<?= htmlspecialchars($firstName ?? '') ?>"
                required minlength="2">

            <label for="last-name">Nom</label>
            <input type="text" id="last-name" name="last-name" value="<?= htmlspecialchars($lastName ?? '') ?>" required
                minlength="2">

            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>

            <label for="pswd">Mot de passe</label>
            <input type="text" id="pswd" name="pswd" value="<?= htmlspecialchars($pswd ?? '') ?>" required min="8">

            <button type="submit">Créer</button>
        </form>
    </main>
</body>

</html>