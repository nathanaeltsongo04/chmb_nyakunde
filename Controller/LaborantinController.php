<?php
require_once __DIR__ . '/../model/Laborantin.php';
require_once __DIR__ . '/../config/Database.php';

class LaborantinController {
    private $laborantin;

    public function __construct() {
        $db = (new Database())->getConnection();
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
