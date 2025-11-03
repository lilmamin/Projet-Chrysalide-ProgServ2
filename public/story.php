<?php
require_once __DIR__ . '/../src/i18n.php';
include __DIR__ . '/templates/header.php';

$id = (int) ($_GET['id'] ?? 0);

// TODO plus tard: récupérer via DB
$story = ['id' => $id ?: 1, 'title' => 'Les Ailes de Chrysalide', 'summary' => "Une métamorphose poétique."];
$chapters = [
    ['position' => 1, 'title' => 'Prologue', 'content' => "Il était une fois..."],
    ['position' => 2, 'title' => 'La rencontre', 'content' => "Michel rencontra son destin."],
];
?>
<h1><?= htmlspecialchars($story['title']) ?></h1>
<p><?= nl2br(htmlspecialchars($story['summary'])) ?></p>
<hr>
<?php foreach ($chapters as $c): ?>
    <article class="chapter">
        <h3>Chapitre <?= (int) $c['position'] ?> — <?= htmlspecialchars($c['title']) ?></h3>
        <div><?= nl2br(htmlspecialchars($c['content'])) ?></div>
    </article>
<?php endforeach; ?>
<?php include __DIR__ . '/templates/footer.php';
