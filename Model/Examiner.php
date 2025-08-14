<?php

class Examiner {
    private $conn;
    private $table = "Examiner";

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
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdExaminer = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un examen
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (IdLaborantin, IdExamen, IdPatient, DateExamen, Resultat, Remarques) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iiisss", 
            $data['IdLaborantin'], 
            $data['IdExamen'], 
            $data['IdPatient'], 
            $data['DateExamen'], 
            $data['Resultat'], 
            $data['Remarques']
        );
        return $stmt->execute();
    }

    // Mettre à jour un examen
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} 
            SET IdLaborantin=?, IdExamen=?, IdPatient=?, DateExamen=?, Resultat=?, Remarques=? 
            WHERE IdExaminer=?");
        $stmt->bind_param(
            "iiisssi", 
            $data['IdLaborantin'], 
            $data['IdExamen'], 
            $data['IdPatient'], 
            $data['DateExamen'], 
            $data['Resultat'], 
            $data['Remarques'], 
            $id
        );
        return $stmt->execute();
    }

    // Supprimer un examen
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdExaminer=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
