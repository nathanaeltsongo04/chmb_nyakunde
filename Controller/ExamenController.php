<?php
require_once "../model/Examen.php";
require_once "../config/Database.php";

class ExamenController {
    private $examen;

    public function __construct() {
        global $db;
        $this->examen = new Examen($db);
    }

    public function index() {
        return $this->examen->getAll();
    }

    public function show($id) {
        return $this->examen->getById($id);
    }

    public function store($data) {
        return $this->examen->create($data);
    }

    public function update($id, $data) {
        return $this->examen->update($id, $data);
    }

    public function destroy($id) {
        return $this->examen->delete($id);
    }
}
