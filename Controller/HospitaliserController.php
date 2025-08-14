<?php
require_once "../model/Hospitaliser.php";
require_once "../config/Database.php";

class HospitaliserController {
    private $hospitaliser;

    public function __construct() {
        global $db;
        $this->hospitaliser = new Hospitaliser($db);
    }

    public function index() {
        return $this->hospitaliser->getAll();
    }

    public function show($id) {
        return $this->hospitaliser->getById($id);
    }

    public function store($data) {
        return $this->hospitaliser->create($data);
    }

    public function update($id, $data) {
        return $this->hospitaliser->update($id, $data);
    }

    public function destroy($id) {
        return $this->hospitaliser->delete($id);
    }
}
