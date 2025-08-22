<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/AuthController.php';

$title = "Connexion";
$pageTitle = "Connexion Agents";

$controller = new AuthController();

// Connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->login($_POST['username'], $_POST['password']);
    if ($result['status']) {
        header("Location: ../../View/Statistique/index.php");
        exit;
    } else {
        
        header("Location: login.php?msg=error");
        
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

<div class="col-lg-4">
    <div class="card shadow">
        <div class="card-body">
            <h5 class="card-title text-center fw-bold"><?= $pageTitle ?></h5>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'error'): ?>
                <div class="alert alert-danger text-center">Identifiants invalides ❌</div>
            <?php endif; ?>

            <form method="POST" class="row g-3">
                <div class="col-12 mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
                </div>
                <div class="col-12 mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </div>
                <div class="col-12 text-center">
                    <p class="small mb-0">Pas de compte ? <a href="register.php">Créer un compte</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
