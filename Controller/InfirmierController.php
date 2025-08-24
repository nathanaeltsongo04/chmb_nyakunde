<?php
/**
 * Contrôleur pour la gestion des infirmiers.
 * Il sert de pont entre la vue (l'interface utilisateur) et le modèle Infirmier.
 * Les méthodes appellent les fonctions correspondantes du modèle pour interagir avec la base de données.
 */

// On inclut le fichier du modèle Infirmier et la configuration de la base de données
// Les chemins d'accès sont relatifs au répertoire du contrôleur.
require_once __DIR__ . '/../model/Infirmier.php';
require_once __DIR__ . '/../config/Database.php';

class InfirmierController {
    private $model;

    /**
     * Constructeur de la classe.
     * Initialise la connexion à la base de données et le modèle Infirmier.
     */
    public function __construct() {
        // Crée une instance de la classe Database
        $db = new Database();
        // Passe la connexion à la base de données au modèle Infirmier
        $this->model = new Infirmier($db->getConnection());
    }

    /**
     * Récupère tous les infirmiers en utilisant le modèle.
     * Cette méthode sera utilisée pour afficher la liste des infirmiers.
     * @return array Un tableau contenant tous les infirmiers.
     */
    public function index() {
        return $this->model->getAll();
    }

    /**
     * Crée un nouvel infirmier.
     * Les données sont généralement passées depuis un formulaire.
     * @param array $data Les données du nouvel infirmier.
     * @return bool|string Le matricule généré en cas de succès, faux sinon.
     */
    public function store($data) {
        return $this->model->create($data);
    }

    /**
     * Met à jour un infirmier existant.
     * @param int $id L'ID de l'infirmier à mettre à jour.
     * @param array $data Les nouvelles données.
     * @return bool Vrai si la mise à jour a réussi, faux sinon.
     */
    public function update($id, $data) {
        return $this->model->update($id, $data);
    }

    /**
     * Supprime un infirmier de la base de données.
     * @param int $id L'ID de l'infirmier à supprimer.
     * @return bool Vrai si la suppression a réussi, faux sinon.
     */
    public function destroy($id) {
        return $this->model->delete($id);
    }
}
