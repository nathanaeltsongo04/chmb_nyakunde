<?php
class Medecin {
    private $conn;
    private $table = "Medecin";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdMedecin = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Générer automatiquement un matricule unique
    private function generateMatricule() {
        $prefix = "MED"; // préfixe pour les médecins
        $uniquePart = time(); // timestamp pour garantir l'unicité
        return $prefix . $uniquePart;
    }

    public function create($data) {
        // Génération du matricule automatique
        $matricule = $this->generateMatricule();

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (Nom, PostNom, Prenom, Specialite, Telephone, Email, Adresse, NumLicence, Matricule) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "sssssssss", 
            $data['Nom'], 
            $data['PostNom'], 
            $data['Prenom'], 
            $data['Specialite'], 
            $data['Telephone'], 
            $data['Email'], 
            $data['Adresse'], 
            $data['NumLicence'], 
            $matricule
        );
        return $stmt->execute() ? $matricule : false; // retourne le matricule généré
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} 
             SET Nom=?, PostNom=?, Prenom=?, Specialite=?, Telephone=?, Email=?, Adresse=?, NumLicence=? 
             WHERE IdMedecin=?"
        );
        $stmt->bind_param(
            "ssssssssi", 
            $data['Nom'], 
            $data['PostNom'], 
            $data['Prenom'], 
            $data['Specialite'], 
            $data['Telephone'], 
            $data['Email'], 
            $data['Adresse'], 
            $data['NumLicence'], 
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdMedecin=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
