<?php

class Laborantin {
    private $conn;
    private $table = "Laborantin";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire tous les laborantins
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire un laborantin par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdLaborantin = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un laborantin
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (Nom, Prenom, Telephone, Email, Adresse) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['Nom'], $data['Prenom'], $data['Telephone'], $data['Email'], $data['Adresse']);
        return $stmt->execute();
    }

    // Mettre à jour un laborantin
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET Nom=?, Prenom=?, Telephone=?, Email=?, Adresse=? WHERE IdLaborantin=?");
        $stmt->bind_param("sssssi", $data['Nom'], $data['Prenom'], $data['Telephone'], $data['Email'], $data['Adresse'], $id);
        return $stmt->execute();
    }

    // Supprimer un laborantin
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdLaborantin=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
