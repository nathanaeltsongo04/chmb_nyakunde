<?php
class Medicament {
    private $conn;
    private $table = "Medicament";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM {$this->table}");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE IdMedicament = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (NomMedicament, Description, DosageStandard, EffetsSecondaires, Prix) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $data['NomMedicament'], $data['Description'], $data['DosageStandard'], $data['EffetsSecondaires'], $data['Prix']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET NomMedicament=?, Description=?, DosageStandard=?, EffetsSecondaires=?, Prix=? WHERE IdMedicament=?");
        $stmt->bind_param("ssssdi", $data['NomMedicament'], $data['Description'], $data['DosageStandard'], $data['EffetsSecondaires'], $data['Prix'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE IdMedicament=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
