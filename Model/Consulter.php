<?php

class Consulter {
    private $conn;
    private $table = "Consulter";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les consultations
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une consultation par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdConsulter = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une consultation
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (IdMedecin, IdPatient, DateConsultation, SignesVitaux, Diagnostic) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iisss", 
            $data['IdMedecin'], 
            $data['IdPatient'], 
            $data['DateConsultation'], 
            $data['SignesVitaux'], 
            $data['Diagnostic']
        );
        return $stmt->execute();
    }

    // Mettre à jour une consultation
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET IdMedecin=?, IdPatient=?, DateConsultation=?, SignesVitaux=?, Diagnostic=? 
            WHERE IdConsulter=?");
        $stmt->bind_param(
            "iisssi", 
            $data['IdMedecin'], 
            $data['IdPatient'], 
            $data['DateConsultation'], 
            $data['SignesVitaux'], 
            $data['Diagnostic'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une consultation
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdConsulter=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
