<?php
/**
 * Modèle de prescription.
 * Gère les interactions avec la table 'PrescrireMedicament' dans la base de données.
 */
class Prescrire
{
    // Connexion à la base de données et nom de la table
    private $conn;
    private $table_name = "PrescrireMedicament";

    // Propriétés de l'objet
    public $IdPrescrireMedicament;
    public $IdMedecin;
    public $IdMedicament;
    public $IdPatient;
    public $DatePrescription;
    public $Dosage;
    public $Duree;

    /**
     * Constructeur avec la connexion à la base de données.
     * @param object $db La connexion à la base de données.
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Récupère toutes les prescriptions avec les noms associés du médecin, du médicament et du patient.
     * @return mysqli_result Le jeu de résultats de la requête.
     */
    public function index()
    {
        $query = "SELECT p.IdPrescrireMedicament, m.Nom AS NomMedecin, d.NomMedicament, pa.Nom AS NomPatient, p.DatePrescription, p.Dosage, p.Duree
                  FROM " . $this->table_name . " p
                  JOIN Medecin m ON p.IdMedecin = m.IdMedecin
                  JOIN Medicament d ON p.IdMedicament = d.IdMedicament
                  JOIN Patient pa ON p.IdPatient = pa.IdPatient
                  ORDER BY p.DatePrescription DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    /**
     * Crée une nouvelle prescription.
     * @return bool Vrai si la création a réussi, sinon faux.
     */
    public function store()
    {
        $query = "INSERT INTO " . $this->table_name . " (IdMedecin, IdMedicament, IdPatient, DatePrescription, Dosage, Duree) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iiisss", $this->IdMedecin, $this->IdMedicament, $this->IdPatient, $this->DatePrescription, $this->Dosage, $this->Duree);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Met à jour une prescription existante.
     * @return bool Vrai si la mise à jour a réussi, sinon faux.
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET IdMedicament=?, IdPatient=?, DatePrescription=?, Dosage=?, Duree=? WHERE IdPrescrireMedicament=?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("iissii", $this->IdMedicament, $this->IdPatient, $this->DatePrescription, $this->Dosage, $this->Duree, $this->IdPrescrireMedicament);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Supprime une prescription par son ID.
     * @return bool Vrai si la suppression a réussi, sinon faux.
     */
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE IdPrescrireMedicament = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param("i", $this->IdPrescrireMedicament);

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
