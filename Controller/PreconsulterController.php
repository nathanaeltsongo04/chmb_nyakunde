<?php
/**
 * Fichier du contrôleur pour la classe Preconsulter.
 * Gère la logique de l'application et les interactions entre le modèle et la vue.
 */

// On inclut les modèles nécessaires
require_once __DIR__ . '/../model/Preconsulter.php';
require_once __DIR__ . '/../model/Infirmier.php';
require_once __DIR__ . '/../model/Patient.php';

class PreconsulterController
{
    private $preconsulterModel;
    private $infirmierModel;
    private $patientModel;

    /**
     * Constructeur avec injection de la connexion à la base de données.
     *
     * @param object $db La connexion à la base de données.
     */
    public function __construct($db)
    {
        // On s'assure que les classes de modèles existent avant de les instancier
        // Cela évite une erreur fatale si un fichier de modèle manque.
        if (class_exists('Preconsulter')) {
            $this->preconsulterModel = new Preconsulter($db);
        }
        if (class_exists('Infirmier')) {
            $this->infirmierModel = new Infirmier($db);
        }
        if (class_exists('Patient')) {
            $this->patientModel = new Patient($db);
        }
    }

    /**
     * Récupère et retourne la liste de toutes les pré-consultations.
     *
     * @return array Un tableau de pré-consultations.
     */
    public function index()
    {
        $result = $this->preconsulterModel->index();
        // Le modèle retourne un jeu de résultats, que nous convertissons en tableau.
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Crée une nouvelle pré-consultation en utilisant les données fournies.
     *
     * @param array $data Un tableau associatif contenant les données de la pré-consultation.
     * @return bool Vrai si la création a réussi, faux sinon.
     */
    public function store($data)
    {
        return $this->preconsulterModel->store($data);
    }

    /**
     * Met à jour une pré-consultation existante avec de nouvelles données.
     *
     * @param int $id L'ID de la pré-consultation à mettre à jour.
     * @param array $data Un tableau associatif contenant les nouvelles données.
     * @return bool Vrai si la mise à jour a réussi, faux sinon.
     */
    public function update($id, $data)
    {
        return $this->preconsulterModel->update($id, $data);
    }

    /**
     * Supprime une pré-consultation par son identifiant.
     *
     * @param int $id L'ID de la pré-consultation à supprimer.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete($id)
    {
        return $this->preconsulterModel->delete($id);
    }

    /**
     * Récupère les données d'une pré-consultation spécifique.
     *
     * @param int $id L'ID de la pré-consultation à récupérer.
     * @return array|null Un tableau associatif de la pré-consultation ou null si non trouvée.
     */
    public function show($id)
    {
        $query = "SELECT * FROM preconsulter WHERE IdPreconsulter = ?";
        $stmt = $this->preconsulterModel->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Récupère la liste de tous les patients en utilisant le modèle Patient.
     * @return array La liste des patients.
     */
    public function getAllPatients()
    {
        // Appelle la méthode index() du modèle Patient pour récupérer les données.
        if ($this->patientModel && method_exists($this->patientModel, 'index')) {
            $result = $this->patientModel->index();
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }
        return [];
    }

    /**
     * Récupère la liste de tous les infirmiers en utilisant le modèle Infirmier.
     * @return array La liste des infirmiers.
     */
    public function getAllInfirmiers()
    {
        // Appelle la méthode index() du modèle Infirmier pour récupérer les données.
        if ($this->infirmierModel && method_exists($this->infirmierModel, 'index')) {
            $result = $this->infirmierModel->index();
            return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        }
        return [];
    }
}
