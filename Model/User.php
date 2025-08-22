<?php
// model/User.php

class User
{
    private $conn;
    private $table = "user";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Récupérer tous les utilisateurs
    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Trouver utilisateur par username
    public function findByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE Username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Vérifier si le matricule existe dans la table correspondant au rôle
    public function matriculeExists($role, $matricule)
    {
        $roleTableMap = [
            'medecin' => 'Medecin',
            'infirmier' => 'Infirmier',
            'laborantin' => 'Laborantin'
        ];

        if (!isset($roleTableMap[$role])) {
            return false;
        }

        $table = $roleTableMap[$role];
        $stmt = $this->conn->prepare("SELECT Matricule FROM {$table} WHERE Matricule = ?");
        $stmt->bind_param("s", $matricule);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Créer un utilisateur
    public function createUser($username, $hashedPassword, $role, $matricule)
    {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (Username, Password, Role, Matricule) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashedPassword, $role, $matricule);
        return $stmt->execute();
    }

    public function getPersonnelInfo($role, $matricule)
    {
        $table = "";
        switch ($role) {
            case "medecin":
                $table = "Medecin";
                break;
            case "infirmier":
                $table = "Infirmier";
                break;
            case "laborantin":
                $table = "Laborantin";
                break;
            default:
                return null;
        }

        $stmt = $this->conn->prepare("SELECT Nom, PostNom, Prenom FROM {$table} WHERE Matricule = ?");
        $stmt->bind_param("s", $matricule);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
