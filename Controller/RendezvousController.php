<?php
require_once "../model/RendezVous.php";
require_once "../config/Database.php";

class RendezVousController {
    private $rendezVous;

    public function __construct() {
        global $db;
        $this->rendezVous = new RendezVous($db);
    }

    public function index() {
        return $this->rendezVous->getAll();
    }

    public function show($id) {
        return $this->rendezVous->getById($id);
    }

    public function store($data) {
        return $this->rendezVous->create($data);
    }

    public function update($id, $data) {
        return $this->rendezVous->update($id, $data);
    }

    public function destroy($id) {
        return $this->rendezVous->delete($id);
    }
}
