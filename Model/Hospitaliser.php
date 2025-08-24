<?php
/**
 * Modèle d'hospitalisation.
 * Gère les interactions avec la table 'Hospitaliser' dans la base de données.
 */
class Hospitaliser
{
    // Connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "Hospitaliser";

    // Propriétés de l'objet
    public $IdHospitaliser;
    public $IdPatient;
    public $IdChambre;
    public $DateEntree;
    public $DateSortie;
    public $MotifHospitalisation;

    /**
     * Constructeur avec la connexion à la base de données.
     * @param object $db La connexion à la base de données.
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Récupère toutes les hospitalisations avec les noms associés du patient et de la chambre.
     * @return mysqli_result Le jeu de résultats de la requête.
     */
    public function index()
    {
        // Correction de la colonne 'NomChambre' par 'Numero'
        $query = "SELECT h.IdHospitaliser, pa.Nom AS NomPatient, c.Numero AS NomChambre, h.DateEntree, h.DateSortie, h.MotifHospitalisation
                  FROM " . $this->table_name . " h
                  JOIN Patient pa ON h.IdPatient = pa.IdPatient
                  JOIN Chambre c ON h.IdChambre = c.IdChambre
                  ORDER BY h.DateEntree DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    /**
     * Crée une nouvelle hospitalisation.
     * @return bool Vrai si la création a réussi, sinon faux.
     */
    public function store()
    {
        $query = "INSERT INTO " . $this->table_name . " (IdPatient, IdChambre, DateEntree, DateSortie, MotifHospitalisation) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iisss", $this->IdPatient, $this->IdChambre, $this->DateEntree, $this->DateSortie, $this->MotifHospitalisation);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Met à jour une hospitalisation existante.
     * @return bool Vrai si la mise à jour a réussi, sinon faux.
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET IdPatient=?, IdChambre=?, DateEntree=?, DateSortie=?, MotifHospitalisation=? WHERE IdHospitaliser=?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iisssi", $this->IdPatient, $this->IdChambre, $this->DateEntree, $this->DateSortie, $this->MotifHospitalisation, $this->IdHospitaliser);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Supprime une hospitalisation par son ID.
     * @return bool Vrai si la suppression a réussi, sinon faux.
     */
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE IdHospitaliser = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->IdHospitaliser);

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
