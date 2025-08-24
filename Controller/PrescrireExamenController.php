<?php
/**
 * Fichier du contrôleur pour la classe PrescrireExamen.
 * Gère la logique de l'application et les interactions entre le modèle et la vue.
 */

require_once __DIR__ . '/../Model/PrescrireExamen.php';

class PrescrireExamenController {
    // Instance du modèle PrescrireExamen
    private PrescrireExamen $prescrireExamenModel;
    private mysqli $db;

    /**
     * Constructeur avec injection de la connexion à la base de données.
     *
     * @param mysqli $db La connexion à la base de données de type mysqli.
     */
    public function __construct(mysqli $db) {
        $this->db = $db;
        $this->prescrireExamenModel = new PrescrireExamen($db);
    }

    /**
     * Récupère et retourne la liste de toutes les prescriptions.
     *
     * @return array|false Un tableau de prescriptions ou false en cas d'erreur.
     */
    public function index(): array|false {
        return $this->prescrireExamenModel->getAll();
    }

    /**
     * Crée une nouvelle prescription en utilisant les données fournies.
     *
     * @param array $data Un tableau associatif contenant les données de la prescription.
     * @return bool Vrai si la création a réussi, faux sinon.
     */
    public function store(array $data): bool {
        // Valide la présence des champs requis avant de passer les données au modèle.
        if (isset($data['IdMedecin'], $data['IdExamen'], $data['IdPatient'], $data['DatePrescription'])) {
            // Le modèle attend un seul tableau associatif.
            return $this->prescrireExamenModel->create($data);
        }
        return false;
    }

    /**
     * Récupère une prescription par son identifiant.
     *
     * @param int $id L'ID de la prescription.
     * @return array|false Un tableau associatif de la prescription ou false si non trouvée.
     */
    public function show(int $id): array|false {
        // Appelle la méthode getOne du modèle pour trouver la prescription.
        return $this->prescrireExamenModel->getOne($id);
    }

    /**
     * Met à jour une prescription existante avec de nouvelles données.
     *
     * @param int $id L'ID de la prescription à mettre à jour.
     * @param array $data Un tableau associatif contenant les nouvelles données.
     * @return bool Vrai si la mise à jour a réussi, faux sinon.
     */
    public function update(int $id, array $data): bool {
        // Le modèle attend l'ID et un seul tableau associatif.
        return $this->prescrireExamenModel->update($id, $data);
    }

    /**
     * Supprime une prescription par son identifiant.
     *
     * @param int $id L'ID de la prescription à supprimer.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete(int $id): bool {
        return $this->prescrireExamenModel->delete($id);
    }
    
    /**
     * Récupère la liste de tous les patients depuis le modèle.
     *
     * @return array Un tableau de patients.
     */
    public function getAllPatients(): array {
        return $this->prescrireExamenModel->getAllPatients();
    }
    
    /**
     * Récupère la liste de tous les examens depuis le modèle.
     *
     * @return array Un tableau d'examens.
     */
    public function getAllExamens(): array {
        return $this->prescrireExamenModel->getAllExamens();
    }
}
