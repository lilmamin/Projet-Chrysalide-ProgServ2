<?php
const DATABASE_CONFIGURATION_FILE = __DIR__ . '/../src/config/database.ini';
require_once __DIR__ . '/config/database.php';

// Documentation : https://www.php.net/manual/fr/function.parse-ini-file.php
$config = parse_ini_file(DATABASE_CONFIGURATION_FILE, true);

if (!$config) {
    throw new Exception("Erreur lors de la lecture du fichier de configuration : " . DATABASE_CONFIGURATION_FILE);
}

$host = $config['host'];
$port = $config['port'];
$database = $config['database'];
$username = $config['username'];
$password = $config['password'];

// Documentation :
//   - https://www.php.net/manual/fr/pdo.connections.php
//   - https://www.php.net/manual/fr/ref.pdo-mysql.connection.php
$pdo = new PDO("mysql:host=$host;port=$port;charset=utf8mb4", $username, $password);

// Création de la base de données si elle n'existe pas
$sql = "CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Sélection de la base de données
$sql = "USE `$database`;";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Création de la table `users` si elle n'existe pas
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    age INT NOT NULL
);";

$stmt = $pdo->prepare($sql);

$stmt->execute();

// Gère la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération des données du formulaire
    $firstName = $_POST["first-name"];
    $lastName = $_POST["last-name"];
    $email = $_POST["email"];
    $age = $_POST["age"];

    $errors = [];

    if (empty($firstName) || strlen($firstName) < 2) {
        $errors[] = "Le prénom doit contenir au moins 2 caractères.";
    }

    if (empty($lastName) || strlen($lastName) < 2) {
        $errors[] = "Le nom doit contenir au moins 2 caractères.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Un email valide est requis.";
    }

    if ($pswd < 8) {
        $errors[] = "Le mot de passe doit contenir au minimum 8 caractères.";
    }

    // Si pas d'erreurs, insertion dans la base de données
    if (empty($errors)) {
        // Définition de la requête SQL pour ajouter un utilisateur
        $sql = "INSERT INTO users (first_name, last_name, email, pswd) VALUES (:first_name, :last_name, :email, :pswd)";

        // Définition de la requête SQL pour ajouter un utilisateur
        $sql = "INSERT INTO users (
            first_name,
            last_name,
            email,
            pswd
        ) VALUES (
            :first_name,
            :last_name,
            :email,
            :pswd
        )";

        // Préparation de la requête SQL
        $stmt = $pdo->prepare($sql);

        // Lien avec les paramètres
        $stmt->bindValue(':first_name', $firstName);
        $stmt->bindValue(':last_name', $lastName);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':pswd', $password);

        // Exécution de la requête SQL pour ajouter un utilisateur
        $stmt->execute();

        // Redirection vers la page d'accueil avec tous les utilisateurs
        header("Location: index.php");
        exit();
    }
}
