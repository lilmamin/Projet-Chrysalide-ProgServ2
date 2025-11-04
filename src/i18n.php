<?php
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'], true)) {
    setcookie('lang', $_GET['lang'], time() + 60 * 60 * 24 * 180, '/');
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$lang = $_COOKIE['lang'] ?? 'fr';
if (!in_array($lang, ['fr', 'en'], true)) {
    $lang = 'fr';
}

// dictionnaire
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
        'read' => 'Lire',
        'logged_out_notice' => 'Vous êtes déconnecté·e ! Connectez-vous pour lire les œuvres !',
        'bookmark' => 'Ajouter à ma bibliothèque',
        'bookmarked' => 'Déjà dans ma bibliothèque',
        'lang_fr' => 'FR',
        'lang_en' => 'EN',
        'all_categories' => 'Toutes les catégories',
        'search' => 'Recherche',
        'search_placeholder' => 'Recherche',
        'likes' => 'Likes',
        'chapters' => 'Chapitres',

        // Genres 
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
        'logged_out_notice' => 'You are logged out! Sign in to read the works!',
        'read' => 'Read',
        'bookmark' => 'Add to Library',
        'bookmarked' => 'Already in Library',
        'lang_fr' => 'FR',
        'lang_en' => 'EN',
        'all_categories' => 'All categories',
        'search' => 'Search',
        'search_placeholder' => 'Search',
        'likes' => 'Likes',
        'chapters' => 'Chapters',

        // Genres
        'genre_romance' => 'Romance',
        'genre_action' => 'Action',
        'genre_historical' => 'Historical',
        'genre_fantasy' => 'Fantasy',
        'genre_horror' => 'Horror',
        'genre_other' => 'Other',
    ],
];

function t(string $key): string
{
    global $T, $lang;
    return $T[$lang][$key] ?? $key;
}
