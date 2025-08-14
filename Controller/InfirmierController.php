<?php
require_once __DIR__ . '/../model/Infirmier.php';

class InfirmierController {
    private $model;

    public function __construct($db) {
        $this->model = new Infirmier($db);
    }

    // Liste tous les infirmiers
    public function index() {
        return $this->model->getAll();
    }

    // Affiche un infirmier par ID
    public function show($id) {
        return $this->model->getById($id);
    }

    // Ajoute un nouvel infirmier
    public function store($data) {
        return $this->model->create($data);
    }

    // Met à jour un infirmier
    public function update($id, $data) {
        return $this->model->update($id, $data);
    }

    // Supprime un infirmier
    public function destroy($id) {
        return $this->model->delete($id);
    }
}
