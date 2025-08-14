<?php

class Chambre {
    private $conn;
    private $table = "Chambre";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les chambres
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une chambre par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdChambre = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une chambre
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (Numero, Type, Etat, PrixParJour) 
            VALUES (?, ?, ?, ?)");
        $stmt->bind_param(
            "sssd", 
            $data['Numero'], 
            $data['Type'], 
            $data['Etat'], 
            $data['PrixParJour']
        );
        return $stmt->execute();
    }

    // Mettre à jour une chambre
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET Numero=?, Type=?, Etat=?, PrixParJour=? 
            WHERE IdChambre=?");
        $stmt->bind_param(
            "sssdi", 
            $data['Numero'], 
            $data['Type'], 
            $data['Etat'], 
            $data['PrixParJour'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une chambre
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdChambre=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
