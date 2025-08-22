<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/AuthController.php';

$title = "Inscription";
$pageTitle = "Créer un compte agent";

$controller = new AuthController();
$message = "";

// Enregistrement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $personnel_id = $_POST['matricule'];

    $result = $controller->register($username, $password, $role, $personnel_id);

    if ($result['status']) {
        header("Location: login.php?msg=success");
        exit;
    } else {
        $message = $result['message'];
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

                <?php if (!empty($message)): ?>
                    <div class="alert alert-danger text-center"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST" class="row g-3">
                    <div class="col-12 mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Nom d'utilisateur" required>
                    </div>
                    <div class="col-12 mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                    </div>
                    <div class="col-12 mb-3">
                        <select name="role" class="form-control" required>
                            <option value="">Sélectionnez le rôle</option>
                            <option value="medecin">Médecin</option>
                            <option value="infirmier">Infirmier</option>
                            <option value="laborantin">Laborantin</option>
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <input type="text" name="matricule" class="form-control" placeholder="Matricule du personnel" required>
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary w-100">Créer un compte</button>
                    </div>
                    <div class="col-12 text-center">
                        <p class="small mb-0">Déjà un compte ? <a href="login.php">Se connecter</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>