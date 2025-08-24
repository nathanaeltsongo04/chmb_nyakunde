<?php
/**
 * Modèle Consultation.php
 * Gère les opérations de base de données pour la table 'consultation'.
 */

class Consultation {
    // Connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "consulter";

    // Propriétés de la consultation
    public $IdConsultation;
    public $IdPatient;
    public $DateConsultation;
    public $Motif;
    public $Diagnostique;

    // Constructeur avec la connexion à la base de données
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Lit toutes les consultations de la base de données.
     * @return PDOStatement Le jeu de résultats de la requête.
     */
    public function readAll() {
        // Sélectionne toutes les colonnes de la table consultation
        $query = "SELECT
                    IdConsultation,
                    IdPatient,
                    DateConsultation,
                    Motif,
                    Diagnostique
                  FROM
                    " . $this->table_name . "
                  ORDER BY
                    DateConsultation DESC"; // Ordonne par date, du plus récent au plus ancien

        // Prépare la requête
        $stmt = $this->conn->prepare($query);

        // Exécute la requête
        $stmt->execute();

        // Récupère les résultats et les retourne sous forme de tableau
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
