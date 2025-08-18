<?php
class Patient {
    private $conn;
    private $table = "Patient"; // Nom exact de la table

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table} ORDER BY IdPatient DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdPatient = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (Nom, PostNom, Prenom, DateNaissance, Sexe, Adresse, Telephone, Email, NumAssurance, GroupeSanguin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssssssss",
            $data['Nom'],
            $data['PostNom'],
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

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET Nom=?, PostNom=?, Prenom=?, DateNaissance=?, Sexe=?, Adresse=?, Telephone=?, Email=?, NumAssurance=?, GroupeSanguin=? WHERE IdPatient=?");
        $stmt->bind_param(
            "ssssssssssi",
            $data['Nom'],
            $data['PostNom'],
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

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdPatient=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
