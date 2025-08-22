<?php
require_once __DIR__ . '/../config/Database.php';

class DashboardController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Afficher le tableau de bord
    public function index() {
        return [
            "patient" => $this->count("patient"),
            "medecin" => $this->count("medecin"),
            "infirmier" => $this->count("infirmier"),
            "laborantin" => $this->count("laborantin"),
        ];
    }

    private function count($table) {
        $sql = "SELECT COUNT(*) AS total FROM $table";
        $stmt = $this->conn->query($sql);
        $row = $stmt->fetch_assoc();
        return $row['total'] ?? 0;
    }
}
