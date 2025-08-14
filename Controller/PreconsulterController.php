<?php
require_once "../model/Preconsulter.php";
require_once "../config/Database.php";

class PreconsulterController {
    private $preconsulter;

    public function __construct() {
        global $db;
        $this->preconsulter = new Preconsulter($db);
    }

    public function index() {
        return $this->preconsulter->getAll();
    }

    public function show($id) {
        return $this->preconsulter->getById($id);
    }

    public function store($data) {
        return $this->preconsulter->create($data);
    }

    public function update($id, $data) {
        return $this->preconsulter->update($id, $data);
    }

    public function destroy($id) {
        return $this->preconsulter->delete($id);
    }
}
