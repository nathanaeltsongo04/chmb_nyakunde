<?php
session_start();
require_once __DIR__ . '/../../../config/Auth_check.php';
require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../controller/PatientController.php';

$title = "Liste Patients";
$pageTitle = "Liste des Patients";

$database = new Database();
$db = $database->getConnection();
$patientController = new PatientController($db);

// Paramètre filtre par nom
$nom_filtre = $_GET['nom'] ?? '';

// Récupération des patients filtrés par nom, triés par Nom ASC
$patients = $patientController->index('Nom', 'ASC', null, null, $nom_filtre);

ob_start();
?>

<div class="col-lg-12">
    <div class="card recent-sales overflow-auto">
        <div class="card-body d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title"><?= $pageTitle ?></h5>
            <div>
                <!-- Bouton pour ouvrir la modale filtre -->
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="bi bi-funnel-fill me-2"></i>Filtrer par nom
                </button>
                <!-- Bouton pour imprimer le tableau affiché -->
                <button class="btn btn-primary" id="printTable">
                    <i class="bi bi-printer-fill me-2"></i>Imprimer
                </button>
            </div>
        </div>

        <!-- Modale filtre -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filtrer patients par nom</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form method="GET" action="">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($nom_filtre) ?>">
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-funnel-fill me-2"></i>Appliquer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau imprimable -->
        <div class="table-responsive printable-content mt-3">
            <h5 class="text-center mb-3">
                Liste des Patients
                <?php if ($nom_filtre): ?> - Nom: <?= htmlspecialchars($nom_filtre) ?><?php endif; ?>
            </h5>
            <table class="table table-bordered table-striped text-center" id="patientsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Post-nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Sexe</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($patients)): ?>
                        <?php foreach ($patients as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['IdPatient']) ?></td>
                                <td><?= htmlspecialchars($p['Nom']) ?></td>
                                <td><?= htmlspecialchars($p['PostNom']) ?></td>
                                <td><?= htmlspecialchars($p['Prenom']) ?></td>
                                <td><?= htmlspecialchars($p['DateNaissance']) ?></td>
                                <td><?= htmlspecialchars($p['Sexe']) ?></td>
                                <td><?= htmlspecialchars($p['Adresse']) ?></td>
                                <td><?= htmlspecialchars($p['Telephone']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Aucun patient trouvé pour ce nom.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Script pour imprimer uniquement le tableau affiché -->
<script>
document.getElementById('printTable').addEventListener('click', function() {
    const printContents = document.querySelector('.printable-content').innerHTML;
    const originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload(); // recharge la page pour restaurer la modale et le reste
});
</script>

<!-- Styles pour impression -->
<style>
@media print {
    body * { visibility: visible; }
    .printable-content { position: absolute; top: 0; left: 0; width: 100%; }
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php
$content = ob_get_clean();
include __DIR__ . '/../../../templates/layout.php';
?>
