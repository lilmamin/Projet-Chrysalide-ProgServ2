<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();

// Versions test en attendant la DB
function login_fake(string $email, string $password): bool
{
    // 2 comptes de test : author / reader
    $fixtures = [
        ['id' => 1, 'email' => 'author@test.dev', 'password' => 'secret', 'role' => 'author'],
        ['id' => 2, 'email' => 'reader@test.dev', 'password' => 'secret', 'role' => 'reader'],
    ];
    foreach ($fixtures as $u) {
        if ($u['email'] === $email && $u['password'] === $password) {
            $_SESSION['user'] = ['id' => $u['id'], 'email' => $u['email'], 'role' => $u['role']];
            return true;
        }
    }
    return false;
}
function register_fake(string $email, string $password, string $role = 'reader'): bool
{
    // à brancher sur DB plus tard
    return true;
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
