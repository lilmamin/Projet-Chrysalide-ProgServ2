<?php
require_once __DIR__ . '/../src/i18n.php';
include __DIR__ . '/templates/header.php';
?>

<header class="page-header">
    <h1><?= t('discover') ?></h1>
    <div class="toolbar">
        <div class="categories">
            <button class="chip active"><?= t('all_categories') ?></button>
            <button class="chip"><?= t('genre_romance') ?></button>
            <button class="chip"><?= t('genre_horror') ?></button>
            <button class="chip"><?= t('genre_historical') ?></button>
            <button class="chip"><?= t('genre_action') ?></button>
            <button class="chip"><?= t('genre_fantasy') ?></button>
        </div>
        <form class="search" action="#" method="get">
            <input type="search" name="q" placeholder="<?= t('search_placeholder') ?>" aria-label="<?= t('search') ?>">
        </form>
    </div>
</header>

<section class="grid">
    <article class="card">
        <a class="cover" href="#">
            <span class="badge"><?= t('genre_romance') ?></span>
        </a>
        <div class="card-body">
            <h3 class="title"><a href="#">Toi et moi jusqu’au bout des cieux</a></h3>
            <p class="author">Olivia Holmes</p>
            <div class="meta">
                <span class="stat" title="<?= t('likes') ?>">1 110 ♥</span>
                <span class="dot" aria-hidden="true">•</span>
                <span class="stat">16 <?= t('chapters') ?></span>
            </div>
        </div>
    </article>

</section>

<?php include __DIR__ . '/templates/footer.php'; ?>