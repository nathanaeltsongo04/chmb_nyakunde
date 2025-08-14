<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/MedecinController.php';

$db = (new Database())->getConnection();
$controller = new MedecinController($db);

// Ajout
if (isset($_POST['ajouter'])) {
    $controller->store($_POST);
    header("Location: index.php");
    exit;
}

// Suppression
if (isset($_GET['delete'])) {
    $controller->destroy($_GET['delete']);
    header("Location: index.php");
    exit;
}

$medecins = $controller->index();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des Médecins</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<h1>Gestion des Médecins</h1>

<form method="POST" class="mb-3">
    <div class="row">
        <div class="col"><input type="text" name="Nom" placeholder="Nom" class="form-control" required></div>
        <div class="col"><input type="text" name="PostNom" placeholder="PostNom" class="form-control" required></div>
        <div class="col"><input type="text" name="Prenom" placeholder="Prénom" class="form-control" required></div>
        <div class="col"><input type="text" name="Specialite" placeholder="Spécialité" class="form-control"></div>
        <div class="col"><input type="text" name="Telephone" placeholder="Téléphone" class="form-control"></div>
        <div class="col"><input type="email" name="Email" placeholder="Email" class="form-control"></div>
        <div class="col"><input type="text" name="Adresse" placeholder="Adresse" class="form-control"></div>
        <div class="col"><input type="text" name="NumLicence" placeholder="Numéro Licence" class="form-control"></div>
        <div class="col"><button name="ajouter" class="btn btn-success">Ajouter</button></div>
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nom</th><th>PostNom</th><th>Prénom</th><th>Spécialité</th>
            <th>Téléphone</th><th>Email</th><th>Adresse</th><th>Licence</th><th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($medecins as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['Nom']) ?></td>
            <td><?= htmlspecialchars($m['PostNom']) ?></td>
            <td><?= htmlspecialchars($m['Prenom']) ?></td>
            <td><?= htmlspecialchars($m['Specialite']) ?></td>
            <td><?= htmlspecialchars($m['Telephone']) ?></td>
            <td><?= htmlspecialchars($m['Email']) ?></td>
            <td><?= htmlspecialchars($m['Adresse']) ?></td>
            <td><?= htmlspecialchars($m['NumLicence']) ?></td>
            <td>
                <a href="?delete=<?= $m['IdMedecin'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
