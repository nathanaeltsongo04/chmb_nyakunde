<?php
require_once "../model/Examiner.php";
require_once "../config/Database.php";

class ExaminerController {
    private $examiner;

    public function __construct() {
        global $db;
        $this->examiner = new Examiner($db);
    }

    public function index() {
        return $this->examiner->getAll();
    }

    public function show($id) {
        return $this->examiner->getById($id);
    }

    public function store($data) {
        return $this->examiner->create($data);
    }

    public function update($id, $data) {
        return $this->examiner->update($id, $data);
    }

    public function destroy($id) {
        return $this->examiner->delete($id);
    }
}
