<?php
require_once "../model/PrescrireMedicament.php";
require_once "../config/Database.php";

class PrescrireMedicamentController {
    private $prescrireMedicament;

    public function __construct() {
        global $db;
        $this->prescrireMedicament = new PrescrireMedicament($db);
    }

    public function index() {
        return $this->prescrireMedicament->getAll();
    }

    public function show($id) {
        return $this->prescrireMedicament->getById($id);
    }

    public function store($data) {
        return $this->prescrireMedicament->create($data);
    }

    public function update($id, $data) {
        return $this->prescrireMedicament->update($id, $data);
    }

    public function destroy($id) {
        return $this->prescrireMedicament->delete($id);
    }
}
