<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . '/config/database.php';


function login(string $email, string $password): bool
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT id, username, email, password_hash, role 
                           FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
        return true;
    }
    return false;
}

function register(string $username, string $email, string $password, string $role = 'reader'): bool
{
    global $pdo;

    $sql = "INSERT INTO users (username, email, password_hash, role, created_at)
            VALUES (:username, :email, :password_hash, :role, NOW())";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role
        ]);
    } catch (PDOException $e) {
        // email déjà utilisé
        return false;
    }
}

function logout(): void
{
    $_SESSION = [];
    session_destroy();
}
function requireAuth(): void
{
    if (empty($_SESSION['user'])) {
        header('Location: /login.php');
        exit;
    }
}
function requireAuthor(): void
{
    requireAuth();
    if ($_SESSION['user']['role'] !== 'author') {
        http_response_code(403);
        exit('Accès refusé');
    }
}
