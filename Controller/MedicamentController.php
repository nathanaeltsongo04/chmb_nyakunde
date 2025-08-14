<?php
require_once "../model/Medicament.php";
require_once "../config/Database.php";

class MedicamentController {
    private $medicament;

    public function __construct() {
        global $db;
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
