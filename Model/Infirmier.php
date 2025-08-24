<?php
/**
 * Modèle Infirmier.php
 * Gère les opérations de base de données pour la table 'Infirmier'.
 * Cette classe utilise l'extension mysqli, comme le modèle Medecin.
 */

class Infirmier {
    private $conn;
    private $table = "Infirmier";

    /**
     * Constructeur pour la classe Infirmier.
     * @param mysqli $db La connexion à la base de données.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Récupère tous les infirmiers de la base de données.
     * @return array Un tableau associatif contenant les données de tous les infirmiers.
     */
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Récupère un infirmier par son ID.
     * @param int $id L'ID de l'infirmier à récupérer.
     * @return array Un tableau associatif des données de l'infirmier, ou null si non trouvé.
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdInfirmier = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Génère un matricule unique pour un nouvel infirmier.
     * @return string Le matricule généré.
     */
    private function generateMatricule() {
        $prefix = "INF"; // préfixe pour les infirmiers
        $uniquePart = uniqid(); // identifiant unique basé sur le temps
        return $prefix . $uniquePart;
    }

    /**
     * Crée un nouvel infirmier dans la base de données.
     * @param array $data Les données du nouvel infirmier.
     * @return bool|string Le matricule généré en cas de succès, ou false en cas d'échec.
     */
    public function create($data) {
        $matricule = $this->generateMatricule();

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (Nom, PostNom, Prenom, Telephone, Email, Adresse, Matricule) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "sssssss", 
            $data['Nom'], 
            $data['PostNom'], 
            $data['Prenom'], 
            $data['Telephone'], 
            $data['Email'], 
            $data['Adresse'], 
            $matricule
        );
        return $stmt->execute() ? $matricule : false;
    }

    /**
     * Met à jour les informations d'un infirmier existant.
     * @param int $id L'ID de l'infirmier à mettre à jour.
     * @param array $data Les nouvelles données de l'infirmier.
     * @return bool Vrai si la mise à jour a réussi, faux sinon.
     */
    public function update($id, $data) {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} 
             SET Nom=?, PostNom=?, Prenom=?, Telephone=?, Email=?, Adresse=? 
             WHERE IdInfirmier=?"
        );
        $stmt->bind_param(
            "ssssssi", 
            $data['Nom'], 
            $data['PostNom'], 
            $data['Prenom'], 
            $data['Telephone'], 
            $data['Email'], 
            $data['Adresse'], 
            $id
        );
        return $stmt->execute();
    }

    /**
     * Supprime un infirmier de la base de données.
     * @param int $id L'ID de l'infirmier à supprimer.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdInfirmier=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
