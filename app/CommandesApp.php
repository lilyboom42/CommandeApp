<?php

class CommandesApp {
    private $clientManager;
    private $commandeManager;

    public function __construct() {
        $db = new Database();
        $this->clientManager = new ClientManager($db);
        $this->commandeManager = new CommandeManager($db);
    }

    public function run() {
        while (true) {
            echo "\n=== Menu Principal ===\n";
            echo "1. Liste des clients\n";
            echo "2. Ajouter un client\n";
            echo "3. Modifier un client\n";
            echo "4. Supprimer un client\n";
            echo "5. Détails d'un client\n";
            echo "6. Ajouter une commande\n";
            echo "7. Modifier une commande\n";
            echo "8. Supprimer une commande\n";
            echo "9. Liste des commandes\n";
            echo "0. Quitter\n";
            $choix = readline("Votre choix : ");

            switch ($choix) {
                case "1":
                    $this->listeClients();
                    break;
                case "2":
                    $this->ajouterClient();
                    break;
                case "3":
                    $this->modifierClient();
                    break;
                case "4":
                    $this->supprimerClient();
                    break;
                case "5":
                    $this->detailsClient();
                    break;
                case "6":
                    $this->ajouterCommande();
                    break;
                case "7":
                    $this->modifierCommande();
                    break;
                case "8":
                    $this->supprimerCommande();
                    break;
                case "9":
                    $this->listeCommandes();
                    break;
                case "0":
                    echo "Au revoir !\n";
                    exit;
                default:
                    echo "Choix invalide. Réessayez.\n";
            }
        }
    }

    private function listeClients() {
        $clients = $this->clientManager->findAll();
        if (empty($clients)) {
            echo "Aucun client trouvé.\n";
        } else {
            echo "Liste des clients :\n";
            foreach ($clients as $client) {
                echo "{$client['ID']}: {$client['Nom']} {$client['Prenom']} - {$client['Ville']}\n";
            }
        }
    }

    private function ajouterClient() {
        echo "Ajout d’un client :\n";
        $nom = readline("Nom : ");
        $prenom = readline("Prénom : ");
        $adresse = readline("Adresse : ");
        $codePostal = readline("Code Postal : ");
        $ville = readline("Ville : ");
        $telephone = readline("Téléphone : ");
        $this->clientManager->add($nom, $prenom, $adresse, $codePostal, $ville, $telephone);
        echo "Client ajouté avec succès.\n";
    }

    private function modifierClient() {
        $id = readline("ID du client à modifier : ");
        if (!$this->clientManager->exists($id)) {
            echo "Client introuvable.\n";
            return;
        }
        echo "Modification du client #$id :\n";
        $nom = readline("Nom : ");
        $prenom = readline("Prénom : ");
        $adresse = readline("Adresse : ");
        $codePostal = readline("Code Postal : ");
        $ville = readline("Ville : ");
        $telephone = readline("Téléphone : ");
        $this->clientManager->edit($id, $nom, $prenom, $adresse, $codePostal, $ville, $telephone);
        echo "Client mis à jour.\n";
    }

    private function supprimerClient() {
        $id = readline("ID du client à supprimer : ");
        if (!$this->clientManager->exists($id)) {
            echo "Client introuvable.\n";
            return;
        }
        $this->clientManager->delete($id);
        echo "Client supprimé avec succès.\n";
    }

    private function detailsClient() {
        $id = readline("ID du client : ");
        $clientData = $this->clientManager->selectById($id);
        if (!$clientData) {
            echo "Client introuvable.\n";
            return;
        }

        $client = $clientData['client'];
        $commandes = $clientData['commandes'];

        echo "Détails du client :\n";
        echo "Nom : {$client['Nom']}\n";
        echo "Prénom : {$client['Prenom']}\n";
        echo "Adresse : {$client['Adresse']}, {$client['CodePostal']} {$client['Ville']}\n";
        echo "Téléphone : {$client['Telephone']}\n";

        echo "Commandes :\n";
        if (empty($commandes)) {
            echo "Aucune commande.\n";
        } else {
            foreach ($commandes as $commande) {
                echo "- {$commande['Date']} : {$commande['Total']} € (ID: {$commande['ID']})\n";
            }
        }
    }

    private function ajouterCommande() {
        $clientId = readline("ID du client : ");
        if (!$this->clientManager->exists($clientId)) {
            echo "Client introuvable.\n";
            return;
        }
        $date = readline("Date de la commande (YYYY-MM-DD) : ");
        $total = readline("Montant total : ");
        $this->commandeManager->add($clientId, $date, $total);
        echo "Commande ajoutée avec succès.\n";
    }

    private function modifierCommande() {
        $commandeId = readline("ID de la commande à modifier : ");
        $commande = $this->commandeManager->findById($commandeId);
        if (!$commande) {
            echo "Commande introuvable.\n";
            return;
        }

        echo "Modification de la commande #{$commande['ID']} pour {$commande['Nom']} {$commande['Prenom']}\n";
        $date = readline("Nouvelle date (YYYY-MM-DD) : ");
        $total = readline("Nouveau montant : ");
        $this->commandeManager->edit($commandeId, $date, $total);
        echo "Commande mise à jour.\n";
    }

    private function supprimerCommande() {
        $commandeId = readline("ID de la commande à supprimer : ");
        $commande = $this->commandeManager->findById($commandeId);
        if (!$commande) {
            echo "Commande introuvable.\n";
            return;
        }
        $this->commandeManager->delete($commandeId);
        echo "Commande supprimée.\n";
    }

    private function listeCommandes() {
        $commandes = $this->commandeManager->findAll();
        if (empty($commandes)) {
            echo "Aucune commande enregistrée.\n";
        } else {
            echo "Liste des commandes :\n";
            foreach ($commandes as $commande) {
                echo "{$commande['ID']} - {$commande['Nom']} {$commande['Prenom']} - {$commande['Date']} - {$commande['Total']} €\n";
            }
        }
    }
}
