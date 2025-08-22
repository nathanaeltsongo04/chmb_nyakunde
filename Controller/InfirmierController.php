<?php
require_once __DIR__ . '/../model/Infirmier.php';
require_once __DIR__ . '/../config/Database.php';

class InfirmierController {
    private $model;

    public function __construct() {
        $db = new Database();
        $this->model = new Infirmier($db->getConnection());
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

    public function getById($id) {
        return $this->model->getById($id);
    }
}
