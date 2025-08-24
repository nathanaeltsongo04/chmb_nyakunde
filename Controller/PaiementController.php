<?php
// On utilise le chemin absolu pour éviter les erreurs
require_once __DIR__ . "/../model/Paiement.php";
require_once __DIR__ . "/../model/Patient.php"; // Nous aurons besoin de ce modèle pour la datalist

class PaiementController {
    private $paiement;
    private $patient; // Ajouter la propriété pour le modèle Patient

    // Le constructeur reçoit la connexion à la base de données
    public function __construct($db) {
        $this->paiement = new Paiement($db);
        $this->patient = new Patient($db);
    }

    public function index() {
        return $this->paiement->getAll();
    }

    // Renommer la méthode pour correspondre au code de l'index
    public function store($data) {
        return $this->paiement->create($data);
    }

    public function update($id, $data) {
        return $this->paiement->update($id, $data);
    }

    // Renommer la méthode pour correspondre au code de l'index
    public function delete($id) {
        return $this->paiement->delete($id);
    }
    
    // Méthode pour récupérer tous les patients
    public function getAllPatients() {
        return $this->patient->getAll();
    }
}
