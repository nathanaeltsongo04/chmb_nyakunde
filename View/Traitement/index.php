<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/PrescrireExamenController.php';

$title = "Prescriptions d'Examens";
$pageTitle = "Prescriptions d'Examens";

$database = new Database();
$db = $database->getConnection();

$controller = new PrescrireExamenController($db);

$connectedMedecinId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';

    if ($method === 'PUT') {
        $controller->update($_POST['IdPrescrireExamen'], $_POST);
        header("Location: index.php?msg=modif");
        exit;
    } else {
        $_POST['IdMedecin'] = $connectedMedecinId;
        // ⚠️ Vérification côté PHP aussi (sécurité serveur)
        if (!empty($_POST['IdExamen']) && !empty($_POST['IdPatient'])) {
            $controller->store($_POST);
            header("Location: index.php?msg=ajout");
            exit;
        } else {
            header("Location: index.php?msg=erreur");
            exit;
        }
    }
}

if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
    header("Location: index.php?msg=suppr");
    exit;
}

$prescriptions = $controller->index();
$examens = $controller->getAllExamens();
$patients = $controller->getAllPatients();

ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#prescrireExamenModal" onclick="openPrescrireExamenModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <div class="alert alert-<?= $_GET['msg']==='ajout'?'success':($_GET['msg']==='modif'?'info':($_GET['msg']==='suppr'?'danger':'warning')) ?>">
                            <?= $_GET['msg']==='ajout'
                                ? 'Prescription ajoutée ✅'
                                : ($_GET['msg']==='modif'
                                    ? 'Prescription modifiée ✏️'
                                    : ($_GET['msg']==='suppr'
                                        ? 'Prescription supprimée 🗑️'
                                        : 'Erreur : veuillez sélectionner un examen et un patient ❌')) ?>
                        </div>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Médecin</th>
                                <th>Examen</th>
                                <th>Patient</th>
                                <th>Date Prescription</th>
                                <th>Commentaires</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($prescriptions)): ?>
                                <?php foreach ($prescriptions as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['IdPrescrireExamen']) ?></td>
                                        <td><?= htmlspecialchars($p['NomMedecin'].' '.$p['PrenomMedecin']) ?></td>
                                        <td><?= htmlspecialchars($p['NomExamen']) ?></td>
                                        <td><?= htmlspecialchars($p['NomPatient'].' '.$p['PostNomPatient'].' '.$p['PrenomPatient']) ?></td>
                                        <td><?= htmlspecialchars($p['DatePrescription']) ?></td>
                                        <td><?= htmlspecialchars($p['Commentaires']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openPrescrireExamenModal(<?= json_encode($p) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($p['IdPrescrireExamen']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7">Aucune prescription trouvée.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="prescrireExamenModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="prescrireExamenForm" method="POST">
                    <input type="hidden" name="IdPrescrireExamen" id="IdPrescrireExamen">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <input type="hidden" name="IdMedecin" id="IdMedecin" value="<?= htmlspecialchars($connectedMedecinId) ?>">

                    <!-- Examen datalist -->
                    <div class="mb-3">
                        <label for="examenSearch" class="form-label">Examen</label>
                        <input list="examensList" id="examenSearch" class="form-control" placeholder="Sélectionner un examen" required>
                        <input type="hidden" name="IdExamen" id="IdExamen">
                        <datalist id="examensList">
                            <?php foreach ($examens as $examen): ?>
                                <option data-id="<?= htmlspecialchars($examen['IdExamen']) ?>" value="<?= htmlspecialchars($examen['NomExamen']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <!-- Patient datalist -->
                    <div class="mb-3">
                        <label for="patientSearch" class="form-label">Patient</label>
                        <input list="patientsList" id="patientSearch" class="form-control" placeholder="Sélectionner un patient" required>
                        <input type="hidden" name="IdPatient" id="IdPatient">
                        <datalist id="patientsList">
                            <?php foreach ($patients as $patient): ?>
                                <option data-id="<?= htmlspecialchars($patient['IdPatient']) ?>" value="<?= htmlspecialchars($patient['Nom'].' '.$patient['PostNom'].' '.$patient['Prenom']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="DatePrescription" class="form-label">Date de prescription</label>
                        <input type="date" name="DatePrescription" id="DatePrescription" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="Commentaires" class="form-label">Commentaires</label>
                        <textarea name="Commentaires" id="Commentaires" class="form-control" placeholder="Commentaires"></textarea>
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
// Ouvrir modal et pré-remplir
function openPrescrireExamenModal(p) {
    const isEdit = p !== null;
    document.getElementById('IdPrescrireExamen').value = isEdit ? p.IdPrescrireExamen : '';

    if(isEdit){
        // Examen
        const examenOption = Array.from(document.getElementById('examensList').options)
            .find(o => o.getAttribute('data-id') == p.IdExamen);
        document.getElementById('examenSearch').value = examenOption ? examenOption.value : '';
        document.getElementById('IdExamen').value = examenOption ? examenOption.getAttribute('data-id') : '';

        // Patient
        const patientOption = Array.from(document.getElementById('patientsList').options)
            .find(o => o.getAttribute('data-id') == p.IdPatient);
        document.getElementById('patientSearch').value = patientOption ? patientOption.value : '';
        document.getElementById('IdPatient').value = patientOption ? patientOption.getAttribute('data-id') : '';
    } else {
        document.getElementById('examenSearch').value = '';
        document.getElementById('IdExamen').value = '';
        document.getElementById('patientSearch').value = '';
        document.getElementById('IdPatient').value = '';
    }

    document.getElementById('DatePrescription').value = isEdit ? p.DatePrescription : '';
    document.getElementById('Commentaires').value = isEdit ? p.Commentaires : '';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Prescription" : "Nouvelle Prescription";

    new bootstrap.Modal(document.getElementById('prescrireExamenModal')).show();
}

// Synchronisation datalist -> hidden input
document.getElementById('examenSearch').addEventListener('input', function(){
    const option = Array.from(document.getElementById('examensList').options)
        .find(o => o.value === this.value);
    document.getElementById('IdExamen').value = option ? option.getAttribute('data-id') : '';
});

document.getElementById('patientSearch').addEventListener('input', function(){
    const option = Array.from(document.getElementById('patientsList').options)
        .find(o => o.value === this.value);
    document.getElementById('IdPatient').value = option ? option.getAttribute('data-id') : '';
});

// Validation avant soumission
document.getElementById('prescrireExamenForm').addEventListener('submit', function(e){
    if(document.getElementById('IdExamen').value === '' || document.getElementById('IdPatient').value === ''){
        e.preventDefault();
        alert("Veuillez sélectionner un examen et un patient valides dans la liste.");
    }
});

// Alert fade
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.classList.add('fade','show');
        setTimeout(() => alert.remove(), 5000);
    });
}, 2000);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
