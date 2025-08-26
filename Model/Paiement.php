<?php
class Paiement {
    private $conn;
    private $table = "paiement";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        // Requête avec JOIN pour récupérer le nom du patient
        $sql = "SELECT p.IdPaiement, p.Montant, p.DatePaiement, p.ModePaiement,
                       pt.Nom AS NomPatient
                FROM paiement p
                INNER JOIN patient pt ON p.IdPatient = pt.IdPatient
                ORDER BY p.DatePaiement DESC";

        $result = $this->conn->query($sql);

        $paiement = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $paiement[] = $row;
            }
        }

        return $paiement;
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO paiement (IdPatient, Montant, DatePaiement, ModePaiement) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $data['IdPatient'], $data['Montant'], $data['DatePaiement'], $data['ModePaiement']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE paiement SET IdPatient=?, Montant=?, DatePaiement=?, ModePaiement=? WHERE IdPaiement=?");
        $stmt->bind_param("idssi", $data['IdPatient'], $data['Montant'], $data['DatePaiement'], $data['ModePaiement'], $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM paiement WHERE IdPaiement=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
