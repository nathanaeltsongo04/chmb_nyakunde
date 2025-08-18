<?php
require_once __DIR__ . '/../model/Chambre.php';
require_once __DIR__ . '/../config/Database.php';

class ChambreController {
    private $chambre;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->chambre = new Chambre($db);
    }

    public function index() {
        return $this->chambre->getAll();
    }

    public function show($id) {
        return $this->chambre->getById($id);
    }

    public function store($data) {
        return $this->chambre->create($data);
    }

    public function update($id, $data) {
        return $this->chambre->update($id, $data);
    }

    public function destroy($id) {
        return $this->chambre->delete($id);
    }
}
