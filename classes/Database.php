<?php
class Database {
    private PDO $pdo;
    private string $dbFile;

    public function __construct(string $dbFile = __DIR__ . '/database.sqlite') {
        $this->dbFile = $dbFile;

        try {
            $this->pdo = new PDO('sqlite:' . $this->dbFile);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->createTables();
        } catch (PDOException $e) {
            die("Erreur de connexion ou création base de données : " . $e->getMessage());
        }
    }

    private function createTables(): void {
        $sql = "CREATE TABLE IF NOT EXISTS clients (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE
        )";

        $this->pdo->exec($sql);
    }

    public function getPDO(): PDO {
        return $this->pdo;
    }
}
