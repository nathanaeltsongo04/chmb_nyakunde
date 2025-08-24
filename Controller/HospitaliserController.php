<?php
/**
 * Contrôleur pour la gestion des hospitalisations.
 * Gère les interactions entre l'utilisateur et le modèle d'hospitalisation.
 */
require_once __DIR__ . '/../model/Hospitaliser.php';

class HospitaliserController
{
    private $hospitaliser;

    /**
     * Constructeur du contrôleur.
     * @param object $db La connexion à la base de données.
     */
    public function __construct($db)
    {
        $this->hospitaliser = new Hospitaliser($db);
    }

    /**
     * Affiche la liste de toutes les hospitalisations.
     * @return array La liste des hospitalisations.
     */
    public function index()
    {
        $result = $this->hospitaliser->index();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ajoute une nouvelle hospitalisation.
     * @param array $data Les données de l'hospitalisation à ajouter.
     */
    public function store($data)
    {
        $this->hospitaliser->IdPatient = $data['IdPatient'];
        $this->hospitaliser->IdChambre = $data['IdChambre'];
        $this->hospitaliser->DateEntree = $data['DateEntree'];
        $this->hospitaliser->DateSortie = $data['DateSortie'] ?? null;
        $this->hospitaliser->MotifHospitalisation = $data['MotifHospitalisation'];
        $this->hospitaliser->store();
    }

    /**
     * Met à jour une hospitalisation existante.
     * @param int $id L'ID de l'hospitalisation à modifier.
     * @param array $data Les nouvelles données.
     */
    public function update($id, $data)
    {
        $this->hospitaliser->IdHospitaliser = $id;
        $this->hospitaliser->IdPatient = $data['IdPatient'];
        $this->hospitaliser->IdChambre = $data['IdChambre'];
        $this->hospitaliser->DateEntree = $data['DateEntree'];
        $this->hospitaliser->DateSortie = $data['DateSortie'] ?? null;
        $this->hospitaliser->MotifHospitalisation = $data['MotifHospitalisation'];
        $this->hospitaliser->update();
    }

    /**
     * Supprime une hospitalisation.
     * @param int $id L'ID de l'hospitalisation à supprimer.
     */
    public function delete($id)
    {
        $this->hospitaliser->IdHospitaliser = $id;
        $this->hospitaliser->delete();
    }

    /**
     * Récupère tous les patients pour les datalists.
     * @return array La liste des patients.
     */
    public function getAllPatients()
    {
        $result = $this->hospitaliser->getAllPatients();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
