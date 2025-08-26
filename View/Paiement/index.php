<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/PaiementController.php';

$title = "Paiements";
$pageTitle = "Gestion des Paiements";

// Connexion à la base de données (mysqli)
$database = new Database();
$db = $database->getConnection(); // doit retourner un objet mysqli

if (!$db) {
    die("Erreur de connexion à la base de données.");
}

// Instancie le contrôleur avec mysqli
$paiementController = new PaiementController($db);

// Récupère tous les paiements et tous les patients
$paiements = $paiementController->getAllPaiements();
$patients  = $paiementController->getAllPatients();

// Gestion POST pour ajout/modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $paiementController->update($_POST['IdPaiement'], $_POST);
        header("Location: index.php?msg=modif");
        exit;
    } else {
        $paiementController->store($_POST);
        header("Location: index.php?msg=ajout");
        exit;
    }
}

// Gestion GET pour suppression
if (isset($_GET['delete'])) {
    $paiementController->delete($_GET['delete']);
    header("Location: index.php?msg=suppr");
    exit;
}

ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#paiementModal" onclick="openPaiementModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Paiement ajouté ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Paiement modifié ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Paiement supprimé 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Montant</th>
                                <th>Date Paiement</th>
                                <th>Mode de Paiement</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($paiements)): ?>
                                <?php foreach ($paiements as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['IdPaiement']) ?></td>
                                        <td><?= htmlspecialchars($p['NomPatient']) ?></td>
                                        <td><?= htmlspecialchars($p['Montant']) ?></td>
                                        <td><?= htmlspecialchars($p['DatePaiement']) ?></td>
                                        <td><?= htmlspecialchars($p['ModePaiement']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openPaiementModal(<?= json_encode($p) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($p['IdPaiement']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Aucun paiement trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajout/modification Paiement -->
<div class="modal fade" id="paiementModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouveau Paiement</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paiementForm" method="POST">
                    <input type="hidden" name="IdPaiement" id="IdPaiement">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <label for="IdPatientInput" class="form-label">Patient</label>
                        <input class="form-control" list="patientOptions" id="IdPatientInput" name="IdPatientInput" placeholder="Rechercher ou sélectionner un patient..." required>
                        <datalist id="patientOptions">
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?= htmlspecialchars($patient['Nom']) ?>" data-id="<?= htmlspecialchars($patient['IdPatient']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="Montant" class="form-label">Montant</label>
                        <input type="number" step="0.01" name="Montant" id="Montant" class="form-control" placeholder="Montant du paiement" required min="0">
                    </div>

                    <div class="mb-3">
                        <label for="DatePaiement" class="form-label">Date de paiement</label>
                        <input type="date" name="DatePaiement" id="DatePaiement" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="ModePaiement" class="form-label">Mode de Paiement</label>
                        <select name="ModePaiement" id="ModePaiement" class="form-control" required>
                            <option value="Espèces">Espèces</option>
                            <option value="Carte bancaire">Carte bancaire</option>
                            <option value="Virement">Virement</option>
                            <option value="Mobile money">Mobile money</option>
                        </select>
                    </div>

                    <div class="text-center mt-3">
                        <button id="submitBtn" class="btn btn-secondary w-50 fw-bold" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openPaiementModal(p) {
    const isEdit = p !== null;
    document.getElementById('IdPaiement').value = isEdit ? p.IdPaiement : '';
    document.getElementById('IdPatientInput').value = isEdit ? p.NomPatient : '';
    document.getElementById('Montant').value = isEdit ? p.Montant : '';
    document.getElementById('DatePaiement').value = isEdit ? p.DatePaiement : '';
    document.getElementById('ModePaiement').value = isEdit ? p.ModePaiement : 'Espèces';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Paiement" : "Nouveau Paiement";

    new bootstrap.Modal(document.getElementById('paiementModal')).show();
}

// Masquer automatiquement les alertes
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.classList.add('fade', 'show');
        setTimeout(() => alert.remove(), 500);
    });
}, 2000);

// Remplacer le nom du patient par l'ID avant envoi
document.getElementById('paiementForm').addEventListener('submit', function(event) {
    const patientName = document.getElementById('IdPatientInput').value;
    const patientOption = document.querySelector(`#patientOptions option[value='${patientName}']`);
    if (patientOption) {
        const patientId = patientOption.dataset.id;
        const patientInput = document.createElement('input');
        patientInput.type = 'hidden';
        patientInput.name = 'IdPatient';
        patientInput.value = patientId;
        this.appendChild(patientInput);
    }
    document.getElementById('IdPatientInput').name = '';
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
