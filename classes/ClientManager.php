<?php
class ClientManager {
    private $pdo;

    public function __construct($database) {
        $this->pdo = $database->getPDO();
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM Client ORDER BY Nom, Prenom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($nom, $prenom, $adresse, $codePostal, $ville, $telephone) {
        $stmt = $this->pdo->prepare("
            INSERT INTO Client (Nom, Prenom, Adresse, CodePostal, Ville, Telephone) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$nom, $prenom, $adresse, $codePostal, $ville, $telephone]);
    }

    public function edit($id, $nom, $prenom, $adresse, $codePostal, $ville, $telephone) {
        $stmt = $this->pdo->prepare("
            UPDATE Client 
            SET Nom = ?, Prenom = ?, Adresse = ?, CodePostal = ?, Ville = ?, Telephone = ? 
            WHERE ID = ?
        ");
        return $stmt->execute([$nom, $prenom, $adresse, $codePostal, $ville, $telephone, $id]);
    }

    public function delete($id) {
        try {
            $this->pdo->beginTransaction();
            $this->pdo->prepare("DELETE FROM Commandes WHERE ClientID = ?")->execute([$id]);
            $result = $this->pdo->prepare("DELETE FROM Client WHERE ID = ?")->execute([$id]);
            $this->pdo->commit();
            return $result;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function selectById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Client WHERE ID = ?");
        $stmt->execute([$id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$client) return null;

        $stmt = $this->pdo->prepare("SELECT * FROM Commandes WHERE ClientID = ? ORDER BY Date DESC");
        $stmt->execute([$id]);
        $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['client' => $client, 'commandes' => $commandes];
    }

    public function exists($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM Client WHERE ID = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
