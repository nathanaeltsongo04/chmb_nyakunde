<?php
// logout.php
require_once __DIR__ . '/../../controller/AuthController.php';

// Instanciation du contrôleur et appel de la fonction logout
$auth = new AuthController();
$auth->logout();

// Redirection vers la page de login
header("Location: login.php");
exit;
