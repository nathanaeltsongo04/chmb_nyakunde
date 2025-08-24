<?php
/**
 * ConsultationController.php
 * Gère les interactions entre les vues et le modèle pour les consultations.
 */

// Utilisez __DIR__ pour construire un chemin absolu et éviter les erreurs
require_once __DIR__ . '/../Model/Consultation.php';
require_once __DIR__ . '/../config/Database.php';

class ConsultationController {
    private $conn;
    private $consultation;

    public function __construct($db) {
        $this->conn = $db;
        $this->consultation = new Consultation($db);
    }

    /**
     * Récupère toutes les consultations.
     * @return array Tableau des consultations.
     */
    public function index() {
        return $this->consultation->readAll();
    }
}
