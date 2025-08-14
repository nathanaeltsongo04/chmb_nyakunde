<?php
require_once "../model/Paiement.php";
require_once "../config/Database.php";

class PaiementController {
    private $paiement;

    public function __construct() {
        global $db;
        $this->paiement = new Paiement($db);
    }

    public function index() {
        return $this->paiement->getAll();
    }

    public function show($id) {
        return $this->paiement->getById($id);
    }

    public function store($data) {
        return $this->paiement->create($data);
    }

    public function update($id, $data) {
        return $this->paiement->update($id, $data);
    }

    public function destroy($id) {
        return $this->paiement->delete($id);
    }
}
