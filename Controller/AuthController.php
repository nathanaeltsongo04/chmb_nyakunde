<?php
// controller/AuthController.php

require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../config/Database.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $db = new Database();
        $this->userModel = new User($db->getConnection());

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Enregistrer un utilisateur
     * @param string $username
     * @param string $password
     * @param string $role
     * @param string $matricule
     * @return array
     */
    public function register($username, $password, $role, $matricule)
    {
        // Vérifier si le nom d'utilisateur est déjà pris
        if ($this->userModel->findByUsername($username)) {
            return ['status' => false, 'message' => "Nom d'utilisateur déjà pris"];
        }

        // Vérifier si le matricule existe dans la table correspondant au rôle
        if (!$this->userModel->matriculeExists($role, $matricule)) {
            return ['status' => false, 'message' => "Le $role avec le matricule $matricule n'existe pas"];
        }

        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Créer l'utilisateur
        $success = $this->userModel->createUser($username, $hashedPassword, $role, $matricule);

        return $success
            ? ['status' => true, 'message' => "Utilisateur créé avec succès"]
            : ['status' => false, 'message' => "Erreur lors de l'inscription"];
    }

    /**
     * Connexion
     * @param string $username
     * @param string $password
     * @return array
     */
    public function login($username, $password)
    {
        $user = $this->userModel->findByUsername($username);

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user_id'] = $user['IdUser'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['role'] = $user['Role'];
            $_SESSION['matricule'] = $user['Matricule'];

            // 🔹 Récupérer les infos du personnel selon son rôle
            $personnel = $this->userModel->getPersonnelInfo($user['Role'], $user['Matricule']);
            if ($personnel) {
                $_SESSION['nom_complet'] = $personnel['PostNom'] . " " . $personnel['Prenom'];
            } else {
                $_SESSION['nom_complet'] = $user['Username']; // fallback
            }

            return ['status' => true, 'message' => "Connexion réussie"];
        } else {
            return ['status' => false, 'message' => "Identifiants incorrects"];
        }
    }


    /**
     * Déconnexion
     * @return array
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        return ['status' => true, 'message' => "Déconnexion réussie"];
    }
}
