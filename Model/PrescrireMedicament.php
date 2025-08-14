<?php

class PrescrireMedicament {
    private $conn;
    private $table = "PrescrireMedicament";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les prescriptions
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une prescription par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdPrescrireMedicament = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une prescription
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (IdMedecin, IdMedicament, IdPatient, DatePrescription, Dosage, Duree) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iiisss", 
            $data['IdMedecin'], 
            $data['IdMedicament'], 
            $data['IdPatient'], 
            $data['DatePrescription'], 
            $data['Dosage'], 
            $data['Duree']
        );
        return $stmt->execute();
    }

    // Mettre à jour une prescription
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET IdMedecin=?, IdMedicament=?, IdPatient=?, DatePrescription=?, Dosage=?, Duree=? 
            WHERE IdPrescrireMedicament=?");
        $stmt->bind_param(
            "iiisssi", 
            $data['IdMedecin'], 
            $data['IdMedicament'], 
            $data['IdPatient'], 
            $data['DatePrescription'], 
            $data['Dosage'], 
            $data['Duree'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une prescription
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdPrescrireMedicament=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
