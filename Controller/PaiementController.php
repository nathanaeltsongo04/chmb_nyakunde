<?php
require_once __DIR__ . "/../model/Paiement.php";
require_once __DIR__ . "/../model/Patient.php";

class PaiementController {
    private $paiement;
    private $patient;

    public function __construct($db) {
        $this->paiement = new Paiement($db);
        $this->patient = new Patient($db);
    }

    public function getAllPaiements() {
        return $this->paiement->getAll(); // doit retourner un tableau associatif
    }

    public function store($data) {
        return $this->paiement->create($data);
    }

    public function update($id, $data) {
        return $this->paiement->update($id, $data);
    }

    public function delete($id) {
        return $this->paiement->delete($id);
    }

    public function getAllPatients() {
        return $this->patient->getAll(); // retourne tableau associatif
    }
}
