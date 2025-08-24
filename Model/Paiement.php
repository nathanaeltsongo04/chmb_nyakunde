<?php
/**
 * Modèle pour la table 'Paiement'.
 * Gère les opérations CRUD (Créer, Lire, Mettre à jour, Supprimer).
 */
class Paiement {
    private $conn;
    // Correction ici : le nom de la table est "Paiement", pas "Paiements"
    private $table = "Paiement";

    public function __construct($db) {
        $this->conn = $db;
    }
    

    /**
     * Lit tous les paiements.
     * @return array
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($query);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Lit un paiement par son ID.
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE IdPaiement = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Crée un nouveau paiement.
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (Montant, DatePaiement, ModePaiement, IdPatient, Description) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "dssis", 
            $data['Montant'], 
            $data['DatePaiement'], 
            $data['ModePaiement'], 
            $data['IdPatient'],
            $data['Description']
        );
        return $stmt->execute();
    }

    /**
     * Met à jour un paiement existant.
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET Montant=?, DatePaiement=?, ModePaiement=?, IdPatient=?, Description=? 
                  WHERE IdPaiement=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param(
            "dssisi", 
            $data['Montant'], 
            $data['DatePaiement'], 
            $data['ModePaiement'], 
            $data['IdPatient'],
            $data['Description'],
            $id
        );
        return $stmt->execute();
    }

    /**
     * Supprime un paiement.
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE IdPaiement = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
}
