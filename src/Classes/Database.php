<?php

/**
 * Classe Database
 * 
 * Gère la connexion à la base de données MySQL via PDO
 * Utilise le fichier de configuration database.ini pour les paramètres de connexion
 * 
 */
class Database
{
    private ?PDO $pdo;

    /**
     * Constructeur : établit la connexion à la base de données
     * 
     * @throws Exception Si le fichier de configuration est introuvable ou invalide
     * @throws PDOException Si la connexion à la base de données échoue
     */
    public function __construct()
    {
        // Chemin vers le fichier de configuration
        $configPath = __DIR__ . '/../config/database.ini';

        // Vérification de l'existence du fichier
        if (!file_exists($configPath)) {
            throw new Exception("Fichier de configuration introuvable : " . $configPath);
        }

        // Lecture du fichier de configuration
        $config = parse_ini_file($configPath);

        if (!$config) {
            throw new Exception("Erreur lors de la lecture du fichier de configuration : " . $configPath);
        }

        // Récupération des paramètres de connexion
        $host = $config['host'] ?? 'localhost';
        $port = $config['port'] ?? 3306;
        $database = $config['database'] ?? '';
        $username = $config['username'] ?? '';
        $password = $config['password'] ?? '';

        // Construction du DSN (Data Source Name)
        $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

        try {
            // Création de la connexion PDO
            $this->pdo = new PDO($dsn, $username, $password);

            // Configuration des options PDO
            // Mode d'erreur : exception (pour une meilleure gestion des erreurs)
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Mode de récupération par défaut : tableau associatif
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            throw new PDOException("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Retourne l'instance PDO pour exécuter des requêtes
     * 
     * @return PDO L'instance de connexion PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * Ferme la connexion à la base de données
     * 
     * Note : PHP ferme automatiquement les connexions PDO à la fin du script,
     * mais cette méthode permet de le faire explicitement si nécessaire
     */
    public function closeConnection(): void
    {
        $this->pdo = null;
    }
}