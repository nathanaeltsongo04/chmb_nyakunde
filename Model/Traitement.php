<?php

class Traitement {
    private $conn;
    private $table = "Traitements";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire tous les traitements
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire un traitement par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdTraitement = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un traitement
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (Description, DateDebut, DateFin, IdPatient, IdMedecin) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssii", 
            $data['Description'], 
            $data['DateDebut'], 
            $data['DateFin'], 
            $data['IdPatient'], 
            $data['IdMedecin']
        );
        return $stmt->execute();
    }

    // Mettre à jour un traitement
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET Description=?, DateDebut=?, DateFin=?, IdPatient=?, IdMedecin=? 
            WHERE IdTraitement=?");
        $stmt->bind_param(
            "sssiii", 
            $data['Description'], 
            $data['DateDebut'], 
            $data['DateFin'], 
            $data['IdPatient'], 
            $data['IdMedecin'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer un traitement
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdTraitement=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
