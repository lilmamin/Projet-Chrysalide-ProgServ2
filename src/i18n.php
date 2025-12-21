<?php
/**
 * Système de traduction multilingue (i18n)
 * Gère les langues FR et EN pour l'interface de l'application
 * Note : Les histoires des utilisateurs ne sont PAS traduites automatiquement
 */

// Gestion du changement de langue via URL (?lang=fr ou ?lang=en)
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fr', 'en'], true)) {
    setcookie('lang', $_GET['lang'], time() + 60 * 60 * 24 * 180, '/');
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Détermination de la langue active (cookie ou défaut FR)
$lang = $_COOKIE['lang'] ?? 'fr';
if (!in_array($lang, ['fr', 'en'], true)) {
    $lang = 'fr';
}

// Dictionnaire de traductions
$T = [
    'fr' => [
        // Navigation générale
        'site_name' => 'Chrysalide',
        'discover' => 'Découvrir',
        'login' => 'Se connecter',
        'logout' => 'Se déconnecter',
        'register' => 'S\'inscrire',
        'author_space' => 'Espace auteur',
        'profile' => 'Mon profil',
        'my_library' => 'Ma bibliothèque',
        'my_stories' => 'Mes histoires',
        'dashboard' => 'Mon espace',
        'welcome' => 'Bienvenue',
        'read' => 'Lire',

        // Messages et notices
        'logged_out_notice' => 'Vous êtes déconnecté·e ! Connectez-vous pour lire les œuvres !',
        'access_denied' => 'Accès refusé',
        'error' => 'Erreur',
        'success' => 'Succès',

        // Actions
        'bookmark' => 'Ajouter à ma bibliothèque',
        'bookmarked' => 'Déjà dans ma bibliothèque',
        'create' => 'Créer',
        'edit' => 'Modifier',
        'delete' => 'Supprimer',
        'save' => 'Enregistrer',
        'cancel' => 'Annuler',
        'back' => 'Retour',
        'continue' => 'Continuer',

        // Langue
        'lang_fr' => 'FR',
        'lang_en' => 'EN',
        'language' => 'Langue',

        // Catégories et recherche
        'all_categories' => 'Toutes les catégories',
        'search' => 'Recherche',
        'search_placeholder' => 'Rechercher une histoire...',

        // Statistiques
        'likes' => 'J\'aime',
        'chapters' => 'Chapitres',
        'views' => 'Vues',
        'stories' => 'Histoires',
        'published' => 'Publiées',
        'drafts' => 'Brouillons',

        // Genres 
        'genre_romance' => 'Romance',
        'genre_action' => 'Action',
        'genre_historical' => 'Historique',
        'genre_fantasy' => 'Fantastique',
        'genre_horror' => 'Horreur',
        'genre_other' => 'Autre',

        // Histoires
        'new_story' => 'Nouvelle histoire',
        'story_title' => 'Titre de l\'histoire',
        'story_summary' => 'Résumé',
        'story_content' => 'Contenu',
        'publish' => 'Publier',
        'unpublish' => 'Dépublier',
        'published_story' => 'Histoire publiée',
        'draft_story' => 'Brouillon',
        'by_author' => 'Par',
        'published_on' => 'Publié le',
        'created_on' => 'Créé le',
        'updated_on' => 'Modifié le',
        'created' => 'Créée le',
        'modified' => 'Modifiée le',

        // Authentification
        'username' => 'Nom d\'utilisateur',
        'email' => 'Email',
        'password' => 'Mot de passe',
        'confirm_password' => 'Confirmer le mot de passe',
        'role' => 'Rôle',
        'reader' => 'Lecteur',
        'author' => 'Auteur',
        'account_confirmed' => 'Compte confirmé',
        'account_pending' => 'En attente de confirmation',

        // Messages de formulaire
        'required_field' => 'Champ obligatoire',
        'min_length' => 'Longueur minimale',
        'max_length' => 'Longueur maximale',
        'invalid_email' => 'Email invalide',
        'passwords_dont_match' => 'Les mots de passe ne correspondent pas',

        // Messages système
        'no_stories' => 'Aucune histoire pour le moment',
        'no_stories_found' => 'Aucune histoire trouvée',
        'create_first_story' => 'Créer ma première histoire',
        'be_first_to_share' => 'Soyez le premier à partager une histoire !',

        // Footer
        'all_rights_reserved' => 'Tous droits réservés',
        'about' => 'À propos',
        'contact' => 'Contact',
        'terms' => 'Conditions d\'utilisation',
        'privacy' => 'Confidentialité',
        'who_are_we' => 'Qui sommes-nous ?',
    ],

    'en' => [
        // Navigation générale
        'site_name' => 'Chrysalide',
        'discover' => 'Discover',
        'login' => 'Log in',
        'logout' => 'Log out',
        'register' => 'Sign up',
        'author_space' => 'Author space',
        'profile' => 'My profile',
        'my_library' => 'My Library',
        'my_stories' => 'My Stories',
        'dashboard' => 'My Space',
        'welcome' => 'Welcome',
        'read' => 'Read',

        // Messages et notices
        'logged_out_notice' => 'You are logged out! Sign in to read the works!',
        'access_denied' => 'Access Denied',
        'error' => 'Error',
        'success' => 'Success',

        // Actions
        'bookmark' => 'Add to Library',
        'bookmarked' => 'Already in Library',
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'back' => 'Back',
        'continue' => 'Continue',

        // Langue
        'lang_fr' => 'FR',
        'lang_en' => 'EN',
        'language' => 'Language',

        // Catégories et recherche
        'all_categories' => 'All categories',
        'search' => 'Search',
        'search_placeholder' => 'Search for a story...',

        // Statistiques
        'likes' => 'Likes',
        'chapters' => 'Chapters',
        'views' => 'Views',
        'stories' => 'Stories',
        'published' => 'Published',
        'drafts' => 'Drafts',

        // Genres
        'genre_romance' => 'Romance',
        'genre_action' => 'Action',
        'genre_historical' => 'Historical',
        'genre_fantasy' => 'Fantasy',
        'genre_horror' => 'Horror',
        'genre_other' => 'Other',

        // Histoires
        'new_story' => 'New Story',
        'story_title' => 'Story Title',
        'story_summary' => 'Summary',
        'story_content' => 'Content',
        'publish' => 'Publish',
        'unpublish' => 'Unpublish',
        'published_story' => 'Published Story',
        'draft_story' => 'Draft',
        'by_author' => 'By',
        'published_on' => 'Published on',
        'created_on' => 'Created on',
        'updated_on' => 'Updated on',
        'created' => 'Created on',
        'modified' => 'Updated on',

        // Authentification
        'username' => 'Username',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'role' => 'Role',
        'reader' => 'Reader',
        'author' => 'Author',
        'account_confirmed' => 'Account Confirmed',
        'account_pending' => 'Pending Confirmation',

        // Messages de formulaire
        'required_field' => 'Required field',
        'min_length' => 'Minimum length',
        'max_length' => 'Maximum length',
        'invalid_email' => 'Invalid email',
        'passwords_dont_match' => 'Passwords don\'t match',

        // Messages système
        'no_stories' => 'No stories yet',
        'no_stories_found' => 'No stories found',
        'create_first_story' => 'Create my first story',
        'be_first_to_share' => 'Be the first to share a story!',

        // Footer
        'all_rights_reserved' => 'All rights reserved',
        'about' => 'About',
        'contact' => 'Contact',
        'terms' => 'Terms of Use',
        'privacy' => 'Privacy',
        'who_are_we' => 'Who are we?',
    ],
];

/**
 * Fonction de traduction
 * 
 * @param string $key Clé de traduction
 * @return string Texte traduit ou clé si traduction non trouvée
 */
function t(string $key): string
{
    global $T, $lang;
    return $T[$lang][$key] ?? $key;
}