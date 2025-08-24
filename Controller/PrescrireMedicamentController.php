<?php
/**
 * Contrôleur pour la gestion des prescriptions.
 * Gère les interactions entre l'utilisateur et le modèle de prescription.
 */
require_once __DIR__ . '/../model/PrescrireMedicament.php';

class PrescrireController
{
    private $prescrire;

    /**
     * Constructeur du contrôleur.
     * @param object $db La connexion à la base de données.
     */
    public function __construct($db)
    {
        $this->prescrire = new Prescrire($db);
    }

    /**
     * Affiche la liste de toutes les prescriptions.
     * @return array La liste des prescriptions.
     */
    public function index()
    {
        $result = $this->prescrire->index();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ajoute une nouvelle prescription.
     * @param array $data Les données de la prescription à ajouter.
     */
    public function store($data)
    {
        $this->prescrire->IdMedecin = $data['IdMedecin'];
        $this->prescrire->IdMedicament = $data['IdMedicament'];
        $this->prescrire->IdPatient = $data['IdPatient'];
        $this->prescrire->DatePrescription = $data['DatePrescription'];
        $this->prescrire->Dosage = $data['Dosage'];
        $this->prescrire->Duree = $data['Duree'];
        $this->prescrire->store();
    }

    /**
     * Met à jour une prescription existante.
     * @param int $id L'ID de la prescription à modifier.
     * @param array $data Les nouvelles données.
     */
    public function update($id, $data)
    {
        $this->prescrire->IdPrescrireMedicament = $id;
        $this->prescrire->IdMedicament = $data['IdMedicament'];
        $this->prescrire->IdPatient = $data['IdPatient'];
        $this->prescrire->DatePrescription = $data['DatePrescription'];
        $this->prescrire->Dosage = $data['Dosage'];
        $this->prescrire->Duree = $data['Duree'];
        $this->prescrire->update();
    }

    /**
     * Supprime une prescription.
     * @param int $id L'ID de la prescription à supprimer.
     */
    public function delete($id)
    {
        $this->prescrire->IdPrescrireMedicament = $id;
        $this->prescrire->delete();
    }

    /**
     * Récupère tous les patients pour les datalists.
     * @return array La liste des patients.
     */
    public function getAllPatients()
    {
        $result = $this->prescrire->getAllPatients();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
