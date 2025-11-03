<?php
require_once __DIR__ . '/../src/i18n.php';
include __DIR__ . '/templates/header.php';

// TODO plus tard: charger depuis la DB
$stories = [
    ['id' => 1, 'title' => 'Les Ailes de Chrysalide', 'summary' => "Une métamorphose poétique.", 'published_at' => '2025-10-01'],
    ['id' => 2, 'title' => 'Sous la pluie', 'summary' => "Romance urbaine.", 'published_at' => '2025-10-12'],
];
?>
<h1><?= t('discover'); ?></h1>
<?php if (!$stories): ?>
    <p>Aucune histoire disponible.</p>
<?php else:
    foreach ($stories as $s): ?>
        <article class="card">
            <h3><a href="/story.php?id=<?= (int) $s['id'] ?>"><?= htmlspecialchars($s['title']) ?></a></h3>
            <p><?= nl2br(htmlspecialchars($s['summary'])) ?></p>
            <a class="btn" href="/story.php?id=<?= (int) $s['id'] ?>"><?= t('read'); ?></a>
        </article>
    <?php endforeach; endif; ?>
<?php include __DIR__ . '/templates/footer.php';
