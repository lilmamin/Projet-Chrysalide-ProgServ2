<?php
$lang = $_COOKIE['lang'] ?? 'fr';
if (!in_array($lang, ['fr', 'en']))
    $lang = 'fr';

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
        'bookmark' => 'Ajouter à ma bibliothèque',
        'bookmarked' => 'Déjà dans ma bibliothèque',
        'lang_fr' => 'FR',
        'lang_en' => 'EN',
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
        'read' => 'Read',
        'bookmark' => 'Add to Library',
        'bookmarked' => 'Already in Library',
        'lang_fr' => 'FR',
        'lang_en' => 'EN',
    ],
];

function t(string $key): string
{
    global $T, $lang;
    return $T[$lang][$key] ?? $key;
}

if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'])) {
    setcookie('lang', $_GET['lang'], time() + 60 * 60 * 24 * 180, '/');
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}