<?php
require_once __DIR__ . '/../model/Patient.php';
require_once __DIR__ . '/../config/Database.php';

class PatientController {
    private $patient;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->patient = new Patient($db);
    }

    public function index() {
        return $this->patient->getAll();
    }

    public function show($id) {
        return $this->patient->getById($id);
    }

    public function store($data) {
        return $this->patient->create($data);
    }

    public function update($id, $data) {
        return $this->patient->update($id, $data);
    }

    public function destroy($id) {
        return $this->patient->delete($id);
    }
}
