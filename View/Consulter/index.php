<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/ConsulterController.php';

$title = "Consultations";
$pageTitle = "Consultations";

$database = new Database();
$db = $database->getConnection();

$connectedMedecinId = $_SESSION['user_id'] ?? null;

if ($db) {
    $controller = new ConsulterController($db);

    $medecins = $controller->getAllMedecins();
    $patients = $controller->getAllPatients();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        if ($method === 'PUT') {
            $controller->update($_POST['IdConsulter'], $_POST);
            header("Location: index.php?msg=modif");
            exit;
        } else {
            $_POST['IdMedecin'] = $connectedMedecinId;

            if(empty($_POST['IdPatient'])) {
                die("Erreur : Veuillez sélectionner un patient dans la liste.");
            }

            $controller->store($_POST);
            header("Location: index.php?msg=ajout");
            exit;
        }
    }

    if (isset($_GET['delete'])) {
        $controller->delete($_GET['delete']);
        header("Location: index.php?msg=suppr");
        exit;
    }

    $consultations = $controller->index();
} else {
    die("Erreur de connexion à la base de données.");
}

ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#consulterModal" onclick="openConsulterModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Consultation ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Consultation modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Consultation supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Médecin</th>
                                <th>Patient</th>
                                <th>Date Consultation</th>
                                <th>Signes Vitaux</th>
                                <th>Diagnostic</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($consultations)): ?>
                                <?php foreach ($consultations as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['IdConsulter']) ?></td>
                                        <td><?= htmlspecialchars($c['NomMedecin']) ?></td>
                                        <td><?= htmlspecialchars($c['NomPatient'].' '.$c['PostNomPatient'].' '.$c['PrenomPatient']) ?></td>
                                        <td><?= htmlspecialchars($c['DateConsultation']) ?></td>
                                        <td><?= htmlspecialchars($c['SignesVitaux']) ?></td>
                                        <td><?= htmlspecialchars($c['Diagnostic']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openConsulterModal(<?= json_encode($c) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($c['IdConsulter']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Aucune consultation trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajout/modif -->
<div class="modal fade" id="consulterModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Consultation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="consulterForm" method="POST">
                    <input type="hidden" name="IdConsulter" id="IdConsulter">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <input type="hidden" name="IdMedecin" id="IdMedecin" value="<?= htmlspecialchars($connectedMedecinId) ?>">

                    <div class="mb-3">
                        <label for="IdPatientInput" class="form-label">Patient</label>
                        <input class="form-control" list="patientOptions" id="IdPatientInput" placeholder="Rechercher ou sélectionner un patient..." required>
                        <input type="hidden" name="IdPatient" id="IdPatient">
                        <datalist id="patientOptions">
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?= htmlspecialchars($patient['Nom'].' '.$patient['PostNom'].' '.$patient['Prenom']) ?>" data-id="<?= htmlspecialchars($patient['IdPatient']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="DateConsultation" class="form-label">Date de consultation</label>
                        <input type="date" name="DateConsultation" id="DateConsultation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="SignesVitaux" class="form-label">Signes vitaux</label>
                        <textarea name="SignesVitaux" id="SignesVitaux" class="form-control" placeholder="Signes vitaux"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="Diagnostic" class="form-label">Diagnostic</label>
                        <textarea name="Diagnostic" id="Diagnostic" class="form-control" placeholder="Diagnostic"></textarea>
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
function openConsulterModal(c) {
    const isEdit = c !== null;
    document.getElementById('IdConsulter').value = isEdit ? c.IdConsulter : '';
    document.getElementById('IdPatientInput').value = isEdit ? c.NomPatient : '';
    document.getElementById('IdPatient').value = isEdit ? c.IdPatient : '';
    document.getElementById('DateConsultation').value = isEdit ? c.DateConsultation : '';
    document.getElementById('SignesVitaux').value = isEdit ? c.SignesVitaux : '';
    document.getElementById('Diagnostic').value = isEdit ? c.Diagnostic : '';
    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Consultation" : "Nouvelle Consultation";
    new bootstrap.Modal(document.getElementById('consulterModal')).show();
}

const patientInput = document.getElementById('IdPatientInput');
const patientIdInput = document.getElementById('IdPatient');

patientInput.addEventListener('input', function() {
    const val = patientInput.value;
    const option = Array.from(document.querySelectorAll('#patientOptions option'))
        .find(opt => opt.value === val);
    patientIdInput.value = option ? option.dataset.id : '';
});

document.getElementById('consulterForm').addEventListener('submit', function(e) {
    if(patientIdInput.value === '') {
        e.preventDefault();
        alert('Veuillez sélectionner un patient dans la liste.');
        patientInput.focus();
    }
});

setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.classList.add('fade', 'show');
        setTimeout(() => alert.remove(), 500);
    });
}, 2000);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
