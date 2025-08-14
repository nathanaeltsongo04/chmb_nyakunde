<?php

class Concerner {
    private $conn;
    private $table = "Concerner";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les relations catégorie-médicament
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une relation par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdConcerner = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une relation catégorie-médicament
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (IdCategorie, IdMedicament) VALUES (?, ?)");
        $stmt->bind_param("ii", $data['IdCategorie'], $data['IdMedicament']);
        return $stmt->execute();
    }

    // Mettre à jour une relation
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET IdCategorie=?, IdMedicament=? WHERE IdConcerner=?");
        $stmt->bind_param("iii", $data['IdCategorie'], $data['IdMedicament'], $id);
        return $stmt->execute();
    }

    // Supprimer une relation
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdConcerner=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
