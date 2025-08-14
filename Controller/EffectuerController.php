<?php
require_once "../model/Effectuer.php";
require_once "../config/Database.php";

class EffectuerController {
    private $effectuer;

    public function __construct() {
        global $db;
        $this->effectuer = new Effectuer($db);
    }

    public function index() {
        return $this->effectuer->getAll();
    }

    public function show($id) {
        return $this->effectuer->getById($id);
    }

    public function store($data) {
        return $this->effectuer->create($data);
    }

    public function update($id, $data) {
        return $this->effectuer->update($id, $data);
    }

    public function destroy($id) {
        return $this->effectuer->delete($id);
    }
}
