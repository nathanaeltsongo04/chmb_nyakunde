<?php

class Hospitaliser {
    private $conn;
    private $table = "Hospitaliser";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lire toutes les hospitalisations
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lire une hospitalisation par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdHospitaliser = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer une hospitalisation
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (IdPatient, IdChambre, DateEntree, DateSortie, MotifHospitalisation) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iisss", 
            $data['IdPatient'], 
            $data['IdChambre'], 
            $data['DateEntree'], 
            $data['DateSortie'], 
            $data['MotifHospitalisation']
        );
        return $stmt->execute();
    }

    // Mettre à jour une hospitalisation
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET IdPatient=?, IdChambre=?, DateEntree=?, DateSortie=?, MotifHospitalisation=? 
            WHERE IdHospitaliser=?");
        $stmt->bind_param(
            "iisssi", 
            $data['IdPatient'], 
            $data['IdChambre'], 
            $data['DateEntree'], 
            $data['DateSortie'], 
            $data['MotifHospitalisation'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer une hospitalisation
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdHospitaliser=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
