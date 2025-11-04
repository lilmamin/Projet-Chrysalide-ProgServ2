<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Connexion à la base de données
 * Assure que $pdo est disponible dans ce fichier
 */
require_once __DIR__ . '/config/database.php';


/**
 * Connexion d'un utilisateur
 * Retourne true si succès, false sinon
 */
function login(string $email, string $password): bool
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT id, username, email, password_hash, role 
                           FROM users 
                           WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {

        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role']
        ];

        return true;
    }

    return false;
}


/**
 * Inscription d'un nouveau user
 * Retourne true si succès, false sinon
 */
function register(string $username, string $email, string $password, string $role = 'reader'): bool
{
    global $pdo;

    $sql = "INSERT INTO users (username, email, password_hash, role, created_at)
            VALUES (:username, :email, :password_hash, :role, NOW())";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role'          => $role
        ]);
    } catch (PDOException $e) {
        // Erreur type : email ou username déjà utilisé
        return false;
    }
}


/**
 * Déconnexion
 */
function logout(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION = [];
    session_destroy();
}


/**
 * Bloque l'accès si user non connecté
 */
function requireAuth(): void
{
    if (empty($_SESSION['user'])) {
        header("Location: /login.php");
        exit;
    }
}


/**
 * Bloque l'accès si user non author
 */
function requireAuthor(): void
{
    requireAuth();

    if ($_SESSION['user']['role'] !== 'author') {
        http_response_code(403);
        exit("Accès refusé - Vous devez être auteur");
    }
}
