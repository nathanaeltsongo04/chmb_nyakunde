<?php
/**
 * Modèle d'examen.
 * Gère les interactions avec la table 'Examiner' dans la base de données.
 */
class Examiner
{
    // Connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "Examiner";

    // Propriétés de l'objet
    public $IdExaminer;
    public $IdLaborantin;
    public $IdExamen;
    public $IdPatient;
    public $DateExamen;
    public $Resultat;
    public $Remarques;

    /**
     * Constructeur avec la connexion à la base de données.
     * @param object $db La connexion à la base de données.
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Récupère toutes les entrées d'examen avec les noms associés.
     * @return mysqli_result Le jeu de résultats de la requête.
     */
    public function index()
    {
        $query = "SELECT e.IdExaminer, l.Nom AS NomLaborantin, ex.NomExamen, pa.Nom AS NomPatient, e.DateExamen, e.Resultat, e.Remarques
                  FROM " . $this->table_name . " e
                  JOIN Laborantin l ON e.IdLaborantin = l.IdLaborantin
                  JOIN Examen ex ON e.IdExamen = ex.IdExamen
                  JOIN Patient pa ON e.IdPatient = pa.IdPatient
                  ORDER BY e.DateExamen DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    /**
     * Crée une nouvelle entrée d'examen.
     * @return bool Vrai si la création a réussi, sinon faux.
     */
    public function store()
    {
        $query = "INSERT INTO " . $this->table_name . " (IdLaborantin, IdExamen, IdPatient, DateExamen, Resultat, Remarques) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iiisss", $this->IdLaborantin, $this->IdExamen, $this->IdPatient, $this->DateExamen, $this->Resultat, $this->Remarques);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Met à jour une entrée d'examen existante.
     * @return bool Vrai si la mise à jour a réussi, sinon faux.
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET IdExamen=?, IdPatient=?, DateExamen=?, Resultat=?, Remarques=? WHERE IdExaminer=?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iissii", $this->IdExamen, $this->IdPatient, $this->DateExamen, $this->Resultat, $this->Remarques, $this->IdExaminer);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Supprime une entrée d'examen par son ID.
     * @return bool Vrai si la suppression a réussi, sinon faux.
     */
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE IdExaminer = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->IdExaminer);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Récupère la liste de tous les patients.
     * @return mysqli_result Le jeu de résultats de la requête.
     */
    public function getAllPatients()
    {
        $query = "SELECT IdPatient, Nom FROM Patient";
        return $this->conn->query($query);
    }
}
?>
