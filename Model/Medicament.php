<?php

class Medicament {
    private $conn;
    private $table = "Medicament";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire tous les médicaments
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire un médicament par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdMedicament = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un médicament
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (NomMedicament, Description, DosageStandard, EffetsSecondaires, Prix) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssd", 
            $data['NomMedicament'], 
            $data['Description'], 
            $data['DosageStandard'], 
            $data['EffetsSecondaires'], 
            $data['Prix']
        );
        return $stmt->execute();
    }

    // Mettre à jour un médicament
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET NomMedicament=?, Description=?, DosageStandard=?, EffetsSecondaires=?, Prix=? 
            WHERE IdMedicament=?");
        $stmt->bind_param(
            "ssssdi", 
            $data['NomMedicament'], 
            $data['Description'], 
            $data['DosageStandard'], 
            $data['EffetsSecondaires'], 
            $data['Prix'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer un médicament
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdMedicament=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
