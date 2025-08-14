<?php

class Categorie {
    private $conn;
    private $table = "Categorie";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les catégories
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une catégorie par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdCategorie = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une catégorie
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (NomCategorie, Description) 
            VALUES (?, ?)");
        $stmt->bind_param(
            "ss", 
            $data['NomCategorie'], 
            $data['Description']
        );
        return $stmt->execute();
    }

    // Mettre à jour une catégorie
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET NomCategorie=?, Description=? 
            WHERE IdCategorie=?");
        $stmt->bind_param(
            "ssi", 
            $data['NomCategorie'], 
            $data['Description'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une catégorie
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdCategorie=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
