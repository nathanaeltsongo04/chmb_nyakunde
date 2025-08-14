<?php
require_once "../model/Administrer.php";
require_once "../config/Database.php";

class AdministrerController {
    private $administrer;

    public function __construct() {
        global $db;
        $this->administrer = new Administrer($db);
    }

    public function index() {
        return $this->administrer->getAll();
    }

    public function show($id) {
        return $this->administrer->getById($id);
    }

    public function store($data) {
        return $this->administrer->create($data);
    }

    public function update($id, $data) {
        return $this->administrer->update($id, $data);
    }

    public function destroy($id) {
        return $this->administrer->delete($id);
    }
}
