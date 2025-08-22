<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/User.php';

$title = "Liste des utilisateurs";
$pageTitle = "Utilisateurs";

// Vérifier si l’utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connexion à la DB et récupération des utilisateurs
$db = new Database();
$userModel = new User($db->getConnection());
$users = $userModel->getAllUsers();

ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (!empty($users)): ?>
                        <table class="table datatable text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Matricule</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Rôle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['IdUser'] ?></td>
                                        <td><?= htmlspecialchars($user['Matricule']) ?></td>
                                        <td><?= htmlspecialchars($user['Username']) ?></td>
                                        <td><?= htmlspecialchars($user['Role']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">Aucun utilisateur trouvé.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
