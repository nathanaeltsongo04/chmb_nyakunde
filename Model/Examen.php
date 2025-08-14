<?php

class Examen {
    private $conn;
    private $table = "Examen";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire tous les examens
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire un examen par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdExamen = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un examen
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (NomExamen, Description, Cout) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $data['NomExamen'], $data['Description'], $data['Cout']);
        return $stmt->execute();
    }

    // Mettre à jour un examen
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET NomExamen=?, Description=?, Cout=? WHERE IdExamen=?");
        $stmt->bind_param("ssdi", $data['NomExamen'], $data['Description'], $data['Cout'], $id);
        return $stmt->execute();
    }

    // Supprimer un examen
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdExamen=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
