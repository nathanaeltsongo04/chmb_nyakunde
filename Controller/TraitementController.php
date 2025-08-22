<?php
require_once __DIR__ . '/../model/Traitement.php';
require_once __DIR__ . '/../config/Database.php';

class TraitementController {
    private $traitement;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->traitement = new Traitement($db);
    }

    public function index() {
        return $this->traitement->getAll();
    }

    public function show($id) {
        return $this->traitement->getById($id);
    }

    public function store($data) {
        return $this->traitement->create($data);
    }

    public function update($id, $data) {
        return $this->traitement->update($id, $data);
    }

    public function destroy($id) {
        return $this->traitement->delete($id);
    }
}
?>
