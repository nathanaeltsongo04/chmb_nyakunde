<?php

class PrescrireExamen {
    private $conn;
    private $table = "PrescrireExamen";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les prescriptions d'examens
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une prescription par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdPrescrireExamen = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une prescription d'examen
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (IdMedecin, IdExamen, IdPatient, DatePrescription, Commentaires) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iiiss", 
            $data['IdMedecin'], 
            $data['IdExamen'], 
            $data['IdPatient'], 
            $data['DatePrescription'], 
            $data['Commentaires']
        );
        return $stmt->execute();
    }

    // Mettre à jour une prescription d'examen
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET IdMedecin=?, IdExamen=?, IdPatient=?, DatePrescription=?, Commentaires=? 
            WHERE IdPrescrireExamen=?");
        $stmt->bind_param(
            "iiissi", 
            $data['IdMedecin'], 
            $data['IdExamen'], 
            $data['IdPatient'], 
            $data['DatePrescription'], 
            $data['Commentaires'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une prescription d'examen
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdPrescrireExamen=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
