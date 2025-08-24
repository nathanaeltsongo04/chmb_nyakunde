<?php
// Vérifier si l'utilisateur est connecté

if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de login si la session n'existe pas
    header("Location: ../../View/Auth/login.php");
    exit;
}
