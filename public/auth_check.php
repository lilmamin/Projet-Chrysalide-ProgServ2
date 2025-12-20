<?php
/**
 * Vérification de l'authentification
 * 
 * Ce fichier doit être inclus en haut de chaque page protégée
 * Il vérifie si l'utilisateur est authentifié, sinon il redirige vers la page de connexion
 * 
 */

// Démarrage de la session
session_start();

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    // L'utilisateur n'est pas authentifié, redirection vers la page de connexion
    header('Location: login.php');
    exit();
}

// L'utilisateur est authentifié, on peut continuer
// Les informations utilisateur sont disponibles dans $_SESSION