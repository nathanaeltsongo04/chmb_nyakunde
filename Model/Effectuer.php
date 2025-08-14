<?php

class Effectuer {
    private $conn;
    private $table = "Effectuer";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les opérations
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une opération par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdEffectuer = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une opération
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (IdPaiement, IdPatient, DateEffectuation, Montant) 
            VALUES (?, ?, ?, ?)");
        $stmt->bind_param(
            "iisd", 
            $data['IdPaiement'], 
            $data['IdPatient'], 
            $data['DateEffectuation'], 
            $data['Montant']
        );
        return $stmt->execute();
    }

    // Mettre à jour une opération
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET IdPaiement=?, IdPatient=?, DateEffectuation=?, Montant=? 
            WHERE IdEffectuer=?");
        $stmt->bind_param(
            "iisdi", 
            $data['IdPaiement'], 
            $data['IdPatient'], 
            $data['DateEffectuation'], 
            $data['Montant'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une opération
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdEffectuer=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
