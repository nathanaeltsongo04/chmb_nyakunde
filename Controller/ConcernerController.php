<?php
require_once "../model/Concerner.php";
require_once "../config/Database.php";

class ConcernerController {
    private $concerner;

    public function __construct() {
        global $db;
        $this->concerner = new Concerner($db);
    }

    public function index() {
        return $this->concerner->getAll();
    }

    public function show($id) {
        return $this->concerner->getById($id);
    }

    public function store($data) {
        return $this->concerner->create($data);
    }

    public function update($id, $data) {
        return $this->concerner->update($id, $data);
    }

    public function destroy($id) {
        return $this->concerner->delete($id);
    }
}
