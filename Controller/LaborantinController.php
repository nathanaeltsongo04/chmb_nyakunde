<?php
require_once "../model/Laborantin.php";
require_once "../config/Database.php";

class LaborantinController {
    private $laborantin;

    public function __construct() {
        global $db;
        $this->laborantin = new Laborantin($db);
    }

    public function index() {
        return $this->laborantin->getAll();
    }

    public function show($id) {
        return $this->laborantin->getById($id);
    }

    public function store($data) {
        return $this->laborantin->create($data);
    }

    public function update($id, $data) {
        return $this->laborantin->update($id, $data);
    }

    public function destroy($id) {
        return $this->laborantin->delete($id);
    }
}
