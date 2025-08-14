<?php

class Preconsulter {
    private $conn;
    private $table = "Preconsulter";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les préconsultations
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une préconsultation par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdPreconsulter = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une préconsultation
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (IdInfirmier, IdPatient, Date, Observations) 
            VALUES (?, ?, ?, ?)");
        $stmt->bind_param(
            "iiss", 
            $data['IdInfirmier'], 
            $data['IdPatient'], 
            $data['Date'], 
            $data['Observations']
        );
        return $stmt->execute();
    }

    // Mettre à jour une préconsultation
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET IdInfirmier=?, IdPatient=?, Date=?, Observations=? 
            WHERE IdPreconsulter=?");
        $stmt->bind_param(
            "iissi", 
            $data['IdInfirmier'], 
            $data['IdPatient'], 
            $data['Date'], 
            $data['Observations'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une préconsultation
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdPreconsulter=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
