<?php
class RendezVous {
    private $conn;
    private $table = "rendezvous";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Récupérer tous les rendez-vous
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Récupérer un rendez-vous par ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdRendezVous = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Créer un rendez-vous
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (DateHeure, IdPatient, IdMedecin, Objet, Statut) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "siiss",
            $data['DateHeure'],
            $data['IdPatient'],
            $data['IdMedecin'],
            $data['Objet'],
            $data['Statut']
        );
        return $stmt->execute();
    }

    // Mettre à jour un rendez-vous
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET DateHeure=?, IdPatient=?, Objet=?, Statut=? WHERE IdRendezVous=?");
        $stmt->bind_param(
            "sissi",
            $data['DateHeure'],
            $data['IdPatient'],
            $data['Objet'],
            $data['Statut'],
            $id
        );
        return $stmt->execute();
    }

    // Supprimer un rendez-vous
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdRendezVous=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
