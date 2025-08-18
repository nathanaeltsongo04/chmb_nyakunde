<?php
require_once __DIR__ . '/../model/Patient.php';

class PatientController {
    private $model;

    public function __construct($db) {
        $this->model = new Patient($db);
    }

    public function index() {
        return $this->model->getAll();
    }

    public function store($data) {
        return $this->model->create($data);
    }

    public function edit($id) {
        return $this->model->getById($id);
    }

    public function update($id, $data) {
        return $this->model->update($id, $data);
    }

    public function destroy($id) {
        return $this->model->delete($id);
    }
}
