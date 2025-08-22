<?php
class Traitement {
    private $conn;
    private $table = "traitement";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Récupérer tous les traitements
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Récupérer un traitement par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdTraitement = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un traitement
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (IdMedecin, IdPatient, Description, DateDebut, DateFin) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "iisss",
            $data['IdMedecin'],
            $data['IdPatient'],
            $data['Description'],
            $data['DateDebut'],
            $data['DateFin']
        );
        return $stmt->execute();
    }

    // Mettre à jour un traitement
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET IdPatient=?, Description=?, DateDebut=?, DateFin=? WHERE IdTraitement=?");
        $stmt->bind_param(
            "isssi",
            $data['IdPatient'],
            $data['Description'],
            $data['DateDebut'],
            $data['DateFin'],
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
?>
