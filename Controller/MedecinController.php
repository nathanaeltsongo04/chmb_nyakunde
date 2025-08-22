<?php
require_once __DIR__ . '/../model/Medecin.php';
require_once __DIR__ . '/../config/Database.php';

class MedecinController {
    private $model;

    public function __construct() {
        $db = new Database();
        $this->model = new Medecin($db->getConnection());
    }

    public function index() {
        return $this->model->getAll();
    }

    public function store($data) {
        return $this->model->create($data);
    }

    public function update($id, $data) {
        return $this->model->update($id, $data);
    }

    public function destroy($id) {
        return $this->model->delete($id);
    }
}
