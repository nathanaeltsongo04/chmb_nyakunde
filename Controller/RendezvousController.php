<?php
require_once __DIR__ . '/../model/RendezVous.php';
require_once __DIR__ . '/../config/Database.php';

class RendezVousController {
    private $rendezvous;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->rendezvous = new RendezVous($db);
    }

    public function index() {
        return $this->rendezvous->getAll();
    }

    public function show($id) {
        return $this->rendezvous->getById($id);
    }

    public function store($data) {
        return $this->rendezvous->create($data);
    }

    public function update($id, $data) {
        return $this->rendezvous->update($id, $data);
    }

    public function destroy($id) {
        return $this->rendezvous->delete($id);
    }
}
?>
