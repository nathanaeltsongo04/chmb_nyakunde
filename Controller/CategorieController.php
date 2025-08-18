<?php
require_once __DIR__ . '/../model/Categorie.php';
require_once __DIR__ . '/../config/Database.php';

class CategorieController {
    private $categorie;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->categorie = new Categorie($db);
    }

    public function index() {
        return $this->categorie->getAll();
    }

    public function show($id) {
        return $this->categorie->getById($id);
    }

    public function store($data) {
        return $this->categorie->create($data);
    }

    public function update($id, $data) {
        return $this->categorie->update($id, $data);
    }

    public function destroy($id) {
        return $this->categorie->delete($id);
    }
}
