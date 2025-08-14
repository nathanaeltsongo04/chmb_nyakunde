<?php

class Administrer {
    private $conn;
    private $table = "Administrer";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les administrations
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une administration par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdAdministrer = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une administration
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (IdInfirmier, IdTraitement, DateAdministration, Observations) 
            VALUES (?, ?, ?, ?)");
        $stmt->bind_param(
            "iiss", 
            $data['IdInfirmier'], 
            $data['IdTraitement'], 
            $data['DateAdministration'], 
            $data['Observations']
        );
        return $stmt->execute();
    }

    // Mettre à jour une administration
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET IdInfirmier=?, IdTraitement=?, DateAdministration=?, Observations=? 
            WHERE IdAdministrer=?");
        $stmt->bind_param(
            "iissi", 
            $data['IdInfirmier'], 
            $data['IdTraitement'], 
            $data['DateAdministration'], 
            $data['Observations'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une administration
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdAdministrer=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
