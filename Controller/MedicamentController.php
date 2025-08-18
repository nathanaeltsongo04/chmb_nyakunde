<?php
require_once __DIR__ . '/../model/Medicament.php';
require_once __DIR__ . '/../config/Database.php';

class MedicamentController {
    private $medicament;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->medicament = new Medicament($db);
    }

    public function index() {
        return $this->medicament->getAll();
    }

    public function show($id) {
        return $this->medicament->getById($id);
    }

    public function store($data) {
        return $this->medicament->create($data);
    }

    public function update($id, $data) {
        return $this->medicament->update($id, $data);
    }

    public function destroy($id) {
        return $this->medicament->delete($id);
    }
}
