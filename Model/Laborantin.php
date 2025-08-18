<?php
class Laborantin {
    private $conn;
    private $table = "Laborantin";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdLaborantin = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (Nom, PostNom, Prenom, Telephone, Email, Adresse) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssss",
            $data['Nom'],
            $data['PostNom'],
            $data['Prenom'],
            $data['Telephone'],
            $data['Email'],
            $data['Adresse']
        );
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET Nom=?, PostNom=?, Prenom=?, Telephone=?, Email=?, Adresse=? WHERE IdLaborantin=?");
        $stmt->bind_param(
            "ssssssi",
            $data['Nom'],
            $data['PostNom'],
            $data['Prenom'],
            $data['Telephone'],
            $data['Email'],
            $data['Adresse'],
            $id
        );
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdLaborantin=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
