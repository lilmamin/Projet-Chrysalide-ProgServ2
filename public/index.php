<?php
require_once __DIR__ . '/../src/i18n.php';
include __DIR__ . '/templates/header.php';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Découverte — Chrysalide</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="app.css" rel="stylesheet">
</head>

<body>

    <main class="container">
        <header class="page-header">
            <h1>Découverte</h1>
            <div class="toolbar">
                <div class="categories">
                    <button class="chip active">Toutes les catégories</button>
                    <button class="chip">Romance</button>
                    <button class="chip">Horreur</button>
                    <button class="chip">Historique</button>
                    <button class="chip">Action</button>
                    <button class="chip">Fantastique</button>
                </div>
                <form class="search" action="#" method="get">
                    <input type="search" name="q" placeholder="Recherche" aria-label="Recherche">
                </form>
            </div>
        </header>

        <!-- Grille d’histoires -->
        <section class="grid">
            <!-- Carte -->
            <article class="card">
                <a class="cover" href="#">
                    <span class="badge">Romance</span>
                    <!-- Image de couverture: remplace par <img src="..." alt=""> -->
                </a>
                <div class="card-body">
                    <h3 class="title"><a href="#">Toi et moi jusqu’au bout des cieux</a></h3>
                    <p class="author">Olivia Holmes</p>
                    <div class="meta">
                        <span class="stat" title="Likes">1 110 <span aria-hidden="true">♥</span></span>
                        <span class="dot" aria-hidden="true">•</span>
                        <span class="stat">16 Chapitres</span>
                    </div>
                </div>
            </article>

            <article class="card">
                <a class="cover alt" href="#">
                    <span class="badge">Horreur</span>
                </a>
                <div class="card-body">
                    <h3 class="title"><a href="#">Une nuit sous un pont illuminé</a></h3>
                    <p class="author">Patrick Jones</p>
                    <div class="meta">
                        <span class="stat">987 ♥</span>
                        <span class="dot">•</span>
                        <span class="stat">14 Chapitres</span>
                    </div>
                </div>
            </article>

            <article class="card">
                <a class="cover" href="#">
                    <span class="badge">Romance</span>
                </a>
                <div class="card-body">
                    <h3 class="title"><a href="#">La chambre de soie</a></h3>
                    <p class="author">Libby Carter</p>
                    <div class="meta">
                        <span class="stat">822 ♥</span>
                        <span class="dot">•</span>
                        <span class="stat">12 Chapitres</span>
                    </div>
                </div>
            </article>

            <article class="card">
                <a class="cover alt" href="#">
                    <span class="badge">Romance</span>
                </a>
                <div class="card-body">
                    <h3 class="title"><a href="#">Ce fut toi</a></h3>
                    <p class="author">Elisabeth Smith</p>
                    <div class="meta">
                        <span class="stat">143 ♥</span>
                        <span class="dot">•</span>
                        <span class="stat">27 Chapitres</span>
                    </div>
                </div>
            </article>

            <article class="card">
                <a class="cover" href="#">
                    <span class="badge">Romance</span>
                </a>
                <div class="card-body">
                    <h3 class="title"><a href="#">La rencontre de Marguerite</a></h3>
                    <p class="author">Otis White</p>
                    <div class="meta">
                        <span class="stat">603 ♥</span>
                        <span class="dot">•</span>
                        <span class="stat">1 Chapitre</span>
                    </div>
                </div>
            </article>

            <article class="card">
                <a class="cover alt" href="#">
                    <span class="badge">Drame</span>
                </a>
                <div class="card-body">
                    <h3 class="title"><a href="#">Plus jamais les mêmes</a></h3>
                    <p class="author">Richie Blanco</p>
                    <div class="meta">
                        <span class="stat">845 ♥</span>
                        <span class="dot">•</span>
                        <span class="stat">6 Chapitres</span>
                    </div>
                </div>
            </article>
            <!-- Duplique/alimente côté backend autant que nécessaire -->
        </section>
    </main>

    <footer class="site-footer">
        <div class="container foot">
            <p>© Chrysalide</p>
        </div>
    </footer>
</body>

</html>

<?php include __DIR__ . '/templates/footer.php';
