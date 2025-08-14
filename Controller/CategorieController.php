<?php
require_once "../model/Categorie.php";
require_once "../config/Database.php";

class CategorieController {
    private $categorie;

    public function __construct() {
        global $db;
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
