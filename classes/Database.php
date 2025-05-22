<?php

class Database {
    private PDO $pdo;
    private string $dbName;

    public function __construct(
        string $host = 'localhost',
        string $username = 'root',
        string $password = 'Cda2025',
        string $dbName = 'gestion_Commandes',
        string $charset = 'utf8mb4'
    ) {
        $this->dbName = $dbName;

        try {
            $pdo = new PDO("mysql:host=$host;charset=$charset", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET $charset COLLATE ${charset}_general_ci");
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=$charset", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTables();
        } catch (PDOException $e) {
            die("Erreur de connexion ou création base de données : " . $e->getMessage());
        }
    }

private function createTables(): void {
    // Table clients
    $sqlClients = "CREATE TABLE IF NOT EXISTS clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(255) NOT NULL,
        prenom VARCHAR(255) NOT NULL,
        adresse VARCHAR(255) NOT NULL,
        code_postal VARCHAR(20) NOT NULL,
        ville VARCHAR(100) NOT NULL,
        telephone VARCHAR(30) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // Table commandes
    $sqlCommandes = "CREATE TABLE IF NOT EXISTS commandes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_id INT NOT NULL,
        date DATE NOT NULL,
        total DECIMAL(10,2) NOT NULL,
        CONSTRAINT fk_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $this->pdo->exec($sqlClients);
    $this->pdo->exec($sqlCommandes);
}


    public function getPDO(): PDO {
        return $this->pdo;
    }
}
