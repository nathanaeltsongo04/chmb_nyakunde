<?php
/**
 * Fichier du contrôleur pour la classe Consulter.
 * Gère la logique de l'application et les interactions entre le modèle et la vue.
 */

require_once __DIR__ . '/../Model/Consulter.php';

class ConsulterController {
    // Instance du modèle Consulter
    private Consulter $consulterModel;
    private mysqli $db;

    /**
     * Constructeur avec injection de la connexion à la base de données.
     *
     * @param mysqli $db La connexion à la base de données de type mysqli.
     */
    public function __construct(mysqli $db) {
        $this->db = $db;
        $this->consulterModel = new Consulter($db);
    }

    /**
     * Récupère et retourne la liste de toutes les consultations.
     *
     * @return array|false Un tableau de consultations ou false en cas d'erreur.
     */
    public function index(): array|false {
        return $this->consulterModel->getAll();
    }

    /**
     * Crée une nouvelle consultation en utilisant les données fournies.
     *
     * @param array $data Un tableau associatif contenant les données de la consultation.
     * @return bool Vrai si la création a réussi, faux sinon.
     */
    public function store(array $data): bool {
        // Valide la présence des champs requis avant de passer les données au modèle.
        if (isset($data['IdMedecin'], $data['IdPatient'], $data['DateConsultation'])) {
            // Le modèle attend un seul tableau associatif.
            return $this->consulterModel->create($data);
        }
        return false;
    }

    /**
     * Récupère une consultation par son identifiant.
     *
     * @param int $id L'ID de la consultation.
     * @return array|false Un tableau associatif de la consultation ou false si non trouvée.
     */
    public function show(int $id): array|false {
        // Appelle la méthode getOne du modèle pour trouver la consultation.
        return $this->consulterModel->getOne($id);
    }

    /**
     * Met à jour une consultation existante avec de nouvelles données.
     *
     * @param int $id L'ID de la consultation à mettre à jour.
     * @param array $data Un tableau associatif contenant les nouvelles données.
     * @return bool Vrai si la mise à jour a réussi, faux sinon.
     */
    public function update(int $id, array $data): bool {
        // Le modèle attend l'ID et un seul tableau associatif.
        return $this->consulterModel->update($id, $data);
    }

    /**
     * Supprime une consultation par son identifiant.
     *
     * @param int $id L'ID de la consultation à supprimer.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function delete(int $id): bool {
        return $this->consulterModel->delete($id);
    }
    
    /**
     * Récupère la liste de tous les médecins depuis le modèle.
     *
     * @return array Un tableau de médecins.
     */
    public function getAllMedecins(): array {
        return $this->consulterModel->getAllMedecins();
    }
    
    /**
     * Récupère la liste de tous les patients depuis le modèle.
     *
     * @return array Un tableau de patients.
     */
    public function getAllPatients(): array {
        return $this->consulterModel->getAllPatients();
    }
}
