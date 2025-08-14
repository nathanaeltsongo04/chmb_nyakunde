<?php

class Paiement {
    private $conn;
    private $table = "Paiements";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire tous les paiements
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire un paiement par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdPaiement = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un paiement
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (Montant, DatePaiement, ModePaiement, IdPatient, Description) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "disss", 
            $data['Montant'], 
            $data['DatePaiement'], 
            $data['ModePaiement'], 
            $data['IdPatient'], 
            $data['Description']
        );
        return $stmt->execute();
    }

    // Mettre à jour un paiement
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET Montant=?, DatePaiement=?, ModePaiement=?, IdPatient=?, Description=? 
            WHERE IdPaiement=?");
        $stmt->bind_param(
            "dsssi", 
            $data['Montant'], 
            $data['DatePaiement'], 
            $data['ModePaiement'], 
            $data['IdPatient'], 
            $data['Description'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer un paiement
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdPaiement=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
