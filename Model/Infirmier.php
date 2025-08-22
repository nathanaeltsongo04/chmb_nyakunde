<?php
class Infirmier {
    private $conn;
    private $table = "Infirmier";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdInfirmier = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (Nom, PostNom, Prenom, Specialite, Telephone, Email, Adresse, NumLicence) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $data['Nom'], $data['PostNom'], $data['Prenom'], $data['Specialite'], $data['Telephone'], $data['Email'], $data['Adresse'], $data['NumLicence']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET Nom=?, PostNom=?, Prenom=?, Specialite=?, Telephone=?, Email=?, Adresse=?, NumLicence=? WHERE IdInfirmier=?");
        $stmt->bind_param("ssssssssi", $data['Nom'], $data['PostNom'], $data['Prenom'], $data['Specialite'], $data['Telephone'], $data['Email'], $data['Adresse'], $data['NumLicence'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdInfirmier=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
