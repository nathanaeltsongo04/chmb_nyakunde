<?php
class Laborantin {
    private $conn;
    private $table = "Laborantin";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdLaborantin = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Générer automatiquement un matricule unique
    private function generateMatricule() {
        $prefix = "LAB"; // préfixe pour les laborantins
        $uniquePart = time(); // timestamp pour garantir l'unicité
        return $prefix . $uniquePart;
    }

    public function create($data) {
        // Génération du matricule automatique
        $matricule = $this->generateMatricule();

        $stmt = $this->conn->prepare(
            "INSERT INTO {$this->table} (Matricule, Nom, PostNom, Prenom, Telephone, Email, Adresse) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "sssssss", 
            $matricule,
            $data['Nom'], 
            $data['PostNom'], 
            $data['Prenom'], 
            $data['Telephone'], 
            $data['Email'], 
            $data['Adresse']
        );
        return $stmt->execute() ? $matricule : false; // retourne le matricule généré
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare(
            "UPDATE {$this->table} 
             SET Nom=?, PostNom=?, Prenom=?, Telephone=?, Email=?, Adresse=? 
             WHERE IdLaborantin=?"
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

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdLaborantin=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}