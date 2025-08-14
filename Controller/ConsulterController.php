<?php
require_once "../model/Consulter.php";
require_once "../config/Database.php";

class ConsulterController {
    private $consulter;

    public function __construct() {
        global $db;
        $this->consulter = new Consulter($db);
    }

    public function index() {
        return $this->consulter->getAll();
    }

    public function show($id) {
        return $this->consulter->getById($id);
    }

    public function store($data) {
        return $this->consulter->create($data);
    }

    public function update($id, $data) {
        return $this->consulter->update($id, $data);
    }

    public function destroy($id) {
        return $this->consulter->delete($id);
    }
}
