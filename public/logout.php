<?php
/**
 * Page de déconnexion (Logout)
 * 
 * Détruit la session de l'utilisateur et le redirige vers la page de connexion
 * Conforme aux bonnes pratiques vues en cours ProgServ2
 */

// Démarrage de la session
session_start();

// Destruction de la session
session_destroy();

// Redirection vers la page de connexion
header('Location: login.php');
exit();