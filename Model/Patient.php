<?php

class Patient {
    private $conn;
    private $table = "Patient";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire tous les patients
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire un patient par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdPatient = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un patient
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (Nom, Prenom, DateNaissance, Sexe, Adresse, Telephone, Email, NumAssurance, GroupeSanguin) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssssss", 
            $data['Nom'], 
            $data['Prenom'], 
            $data['DateNaissance'], 
            $data['Sexe'], 
            $data['Adresse'], 
            $data['Telephone'], 
            $data['Email'], 
            $data['NumAssurance'], 
            $data['GroupeSanguin']
        );
        return $stmt->execute();
    }

    // Mettre à jour un patient
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET Nom=?, Prenom=?, DateNaissance=?, Sexe=?, Adresse=?, Telephone=?, Email=?, NumAssurance=?, GroupeSanguin=? 
            WHERE IdPatient=?");
        $stmt->bind_param(
            "sssssssssi", 
            $data['Nom'], 
            $data['Prenom'], 
            $data['DateNaissance'], 
            $data['Sexe'], 
            $data['Adresse'], 
            $data['Telephone'], 
            $data['Email'], 
            $data['NumAssurance'], 
            $data['GroupeSanguin'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer un patient
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdPatient=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
