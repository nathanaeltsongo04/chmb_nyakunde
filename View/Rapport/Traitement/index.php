<?php
/**
 * Fichier index pour le rapport des traitements.
 * Affiche les traitements enregistrés pour les patients.
 */

session_start();
require_once __DIR__ . '/../../../config/Auth_check.php';
require_once __DIR__ . '/../../../config/Database.php';
// Assurez-vous que le chemin vers votre contrôleur est correct
require_once __DIR__ . '/../../../controller/TraitementController.php';

$title = "Rapport Traitements";
$pageTitle = "Rapport des Traitements";

// Crée une instance de la classe Database
$database = new Database();
$db = $database->getConnection();
$traitementController = new TraitementController($db);

// Récupère toutes les données de traitements
$traitements = $traitementController->index();

ob_start();
?>

<!-- Contenu HTML de la page -->
<div class="col-lg-12">
    <div class="card recent-sales overflow-auto">
        <div class="card-body">
            <h5 class="card-title"><?= $pageTitle ?></h5>

            <table class="table datatable text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Description</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($traitements)): ?>
                        <?php foreach ($traitements as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t['IdTraitement']) ?></td>
                                <td><?= htmlspecialchars($t['NomPatient']) ?></td>
                                <td><?= htmlspecialchars($t['DateDebut']) ?></td>
                                <td><?= htmlspecialchars($t['DateFin']) ?></td>
                                <td><?= htmlspecialchars($t['Description']) ?></td>
                                <td><span class="badge bg-<?= ($t['Statut'] === 'Terminé') ? 'success' : 'warning' ?>"><?= htmlspecialchars($t['Statut']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Aucun traitement trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../../templates/layout.php';
?>
