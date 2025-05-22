<?php
class CommandeManager {
    private $pdo;

    public function __construct($database) {
        $this->pdo = $database->getPDO();
    }

    public function add($clientId, $date, $total) {
        $stmt = $this->pdo->prepare("
            INSERT INTO Commandes (ClientID, Date, Total) 
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$clientId, $date, $total]);
    }

    public function edit($id, $date, $total) {
        $stmt = $this->pdo->prepare("
            UPDATE Commandes 
            SET Date = ?, Total = ? 
            WHERE ID = ?
        ");
        return $stmt->execute([$date, $total, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Commandes WHERE ID = ?");
        return $stmt->execute([$id]);
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT c.*, cl.Nom, cl.Prenom 
            FROM Commandes c 
            JOIN Client cl ON c.ClientID = cl.ID 
            WHERE c.ID = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $stmt = $this->pdo->query("
            SELECT c.*, cl.Nom, cl.Prenom 
            FROM Commandes c 
            JOIN Client cl ON c.ClientID = cl.ID 
            ORDER BY c.Date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
