<?php
require_once __DIR__ . '/../Model/Laborantin.php';

class LaborantinController {
    private $model;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->model = new Laborantin($db);
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