<?php
require_once "../model/PrescrireExamen.php";
require_once "../config/Database.php";

class PrescrireExamenController {
    private $prescrireExamen;

    public function __construct() {
        global $db;
        $this->prescrireExamen = new PrescrireExamen($db);
    }

    public function index() {
        return $this->prescrireExamen->getAll();
    }

    public function show($id) {
        return $this->prescrireExamen->getById($id);
    }

    public function store($data) {
        return $this->prescrireExamen->create($data);
    }

    public function update($id, $data) {
        return $this->prescrireExamen->update($id, $data);
    }

    public function destroy($id) {
        return $this->prescrireExamen->delete($id);
    }
}
