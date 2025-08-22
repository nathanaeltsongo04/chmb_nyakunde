<?php
// Vérifier si l'utilisateur est connecté
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirige vers la page de login si la session n'existe pas
    header("Location: ../../View/Auth/login.php");
    exit;
}

// // Vérifier si l'utilisateur a le rôle de "medecin"
// if ($_SESSION['role'] !== 'medecin') {
//     // Redirige vers la page d'accueil si l'utilisateur n'a pas le rôle de "medecin"
//     header("Location: index.php");
//     exit;
// }