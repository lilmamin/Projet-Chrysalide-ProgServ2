<?php
// 1) Traiter le changement de langue AVANT tout output
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'], true)) {
    // cookie 6 mois, disponible sur tout le site
    setcookie('lang', $_GET['lang'], time() + 60 * 60 * 24 * 180, '/');
    // rediriger vers l’URL sans le paramètre
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// 2) Déterminer la langue courante (cookie -> fallback FR)
$lang = $_COOKIE['lang'] ?? 'fr';
if (!in_array($lang, ['fr', 'en'], true)) {
    $lang = 'fr';
}

// 3) Dictionnaire
$T = [
    'fr' => [
        'site_name' => 'Chrysalide',
        'discover' => 'Découvrir',
        'login' => 'Se connecter',
        'logout' => 'Se déconnecter',
        'register' => 'S’inscrire',
        'author_space' => 'Espace auteur',
        'profile' => 'Mon profil',
        'my_library' => 'Ma bibliothèque',
        'welcome' => 'Bienvenue',
        'home_intro' => 'Bienvenue sur Chrysalide. Rendez-vous sur <a href="%s">Découvrir</a> pour parcourir les histoires.',
        'read' => 'Lire',
        'logged_out_notice' => 'Vous êtes déconnecté·e ! Connectez-vous pour lire les œuvres !',
        'bookmark' => 'Ajouter à ma bibliothèque',
        'bookmarked' => 'Déjà dans ma bibliothèque',
        'lang_fr' => 'FR',
        'lang_en' => 'EN',

        // Ajouts pour la page Découverte
        'all_categories' => 'Toutes les catégories',
        'search' => 'Recherche',
        'search_placeholder' => 'Recherche',
        'likes' => 'Likes',
        'chapters' => 'Chapitres',

        // Genres (UI)
        'genre_romance' => 'Romance',
        'genre_action' => 'Action',
        'genre_historical' => 'Historique',
        'genre_fantasy' => 'Fantastique',
        'genre_horror' => 'Horreur',
        'genre_other' => 'Autre',
    ],
    'en' => [
        'site_name' => 'Chrysalide',
        'discover' => 'Discover',
        'login' => 'Log in',
        'logout' => 'Log out',
        'register' => 'Sign up',
        'author_space' => 'Author space',
        'profile' => 'My profile',
        'my_library' => 'My Library',
        'welcome' => 'Welcome',
        'home_intro' => 'Welcome to Chrysalide. Head to <a href="%s">Discover</a> to browse stories.',
        'logged_out_notice' => 'You are logged out! Sign in to read the works!',
        'read' => 'Read',
        'bookmark' => 'Add to Library',
        'bookmarked' => 'Already in Library',
        'lang_fr' => 'FR',
        'lang_en' => 'EN',

        // Discover page
        'all_categories' => 'All categories',
        'search' => 'Search',
        'search_placeholder' => 'Search',
        'likes' => 'Likes',
        'chapters' => 'Chapters',

        // Genres (UI)
        'genre_romance' => 'Romance',
        'genre_action' => 'Action',
        'genre_historical' => 'Historical',
        'genre_fantasy' => 'Fantasy',
        'genre_horror' => 'Horror',
        'genre_other' => 'Other',
    ],
];

// 4) Helper de traduction
function t(string $key): string
{
    global $T, $lang;
    return $T[$lang][$key] ?? $key;
}
