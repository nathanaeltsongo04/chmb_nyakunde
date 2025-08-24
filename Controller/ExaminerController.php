<?php
/**
 * Contrôleur pour la gestion des examens de laboratoire.
 * Gère les interactions entre l'utilisateur et le modèle d'examen.
 */
require_once __DIR__ . '/../model/Examiner.php';

class ExaminerController
{
    private $examiner;

    /**
     * Constructeur du contrôleur.
     * @param object $db La connexion à la base de données.
     */
    public function __construct($db)
    {
        $this->examiner = new Examiner($db);
    }

    /**
     * Affiche la liste de tous les examens.
     * @return array La liste des examens.
     */
    public function index()
    {
        $result = $this->examiner->index();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Ajoute un nouvel examen.
     * @param array $data Les données de l'examen à ajouter.
     */
    public function store($data)
    {
        $this->examiner->IdLaborantin = $data['IdLaborantin'];
        $this->examiner->IdExamen = $data['IdExamen'];
        $this->examiner->IdPatient = $data['IdPatient'];
        $this->examiner->DateExamen = $data['DateExamen'];
        $this->examiner->Resultat = $data['Resultat'];
        $this->examiner->Remarques = $data['Remarques'];
        $this->examiner->store();
    }

    /**
     * Met à jour un examen existant.
     * @param int $id L'ID de l'examen à modifier.
     * @param array $data Les nouvelles données.
     */
    public function update($id, $data)
    {
        $this->examiner->IdExaminer = $id;
        $this->examiner->IdExamen = $data['IdExamen'];
        $this->examiner->IdPatient = $data['IdPatient'];
        $this->examiner->DateExamen = $data['DateExamen'];
        $this->examiner->Resultat = $data['Resultat'];
        $this->examiner->Remarques = $data['Remarques'];
        $this->examiner->update();
    }

    /**
     * Supprime un examen.
     * @param int $id L'ID de l'examen à supprimer.
     */
    public function delete($id)
    {
        $this->examiner->IdExaminer = $id;
        $this->examiner->delete();
    }

    /**
     * Récupère tous les patients pour les datalists.
     * @return array La liste des patients.
     */
    public function getAllPatients()
    {
        $result = $this->examiner->getAllPatients();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
