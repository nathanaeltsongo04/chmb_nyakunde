<?php
/**
 * Fichier index pour la gestion des hospitalisations.
 * Ce script gère les requêtes d'ajout, de modification et de suppression.
 */

session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/HospitaliserController.php';
require_once __DIR__ . '/../../controller/ChambreController.php';

$title = "Hospitalisations";
$pageTitle = "Hospitalisations";

$database = new Database();
$db = $database->getConnection();

if ($db) {
    $controller = new HospitaliserController($db);
    $chambreController = new ChambreController($db);
    
    $chambres = $chambreController->index();
    $patients = $controller->getAllPatients(); 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        if ($method === 'PUT') {
            $controller->update($_POST['IdHospitaliser'], $_POST);
            header("Location: index.php?msg=modif");
            exit;
        } else {
            if(empty($_POST['IdPatient']) || empty($_POST['IdChambre'])) {
                die("Erreur : Veuillez sélectionner un patient et une chambre dans les listes.");
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

    $hospitalisations = $controller->index();
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
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#hospitaliserModal" onclick="openHospitaliserModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Hospitalisation ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Hospitalisation modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Hospitalisation supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Chambre</th>
                                <th>Date d'entrée</th>
                                <th>Date de sortie</th>
                                <th>Motif</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($hospitalisations)): ?>
                                <?php foreach ($hospitalisations as $h): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($h['IdHospitaliser']) ?></td>
                                        <td><?= htmlspecialchars($h['NomPatient'].' '.$h['PostNomPatient'].' '.$h['PrenomPatient']) ?></td>
                                        <td><?= htmlspecialchars($h['NomChambre']) ?></td>
                                        <td><?= htmlspecialchars($h['DateEntree']) ?></td>
                                        <td><?= htmlspecialchars($h['DateSortie'] ?? 'Non spécifiée') ?></td>
                                        <td><?= htmlspecialchars($h['MotifHospitalisation']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openHospitaliserModal(<?= json_encode($h) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($h['IdHospitaliser']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Aucune hospitalisation trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hospitaliserModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Hospitalisation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="hospitaliserForm" method="POST">
                    <input type="hidden" name="IdHospitaliser" id="IdHospitaliser">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <label for="IdPatientInput" class="form-label">Patient</label>
                        <input class="form-control" list="patientOptions" id="IdPatientInput" placeholder="Rechercher ou sélectionner un patient..." required>
                        <input type="hidden" name="IdPatient" id="IdPatient">
                        <datalist id="patientOptions">
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?= htmlspecialchars($patient['Nom'] .' '.$patient['PostNom'].' '.$patient['Prenom']) ?>" data-id="<?= htmlspecialchars($patient['IdPatient']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="IdChambreInput" class="form-label">Chambre</label>
                        <input class="form-control" list="chambreOptions" id="IdChambreInput" placeholder="Rechercher ou sélectionner une chambre..." required>
                        <input type="hidden" name="IdChambre" id="IdChambre">
                        <datalist id="chambreOptions">
                            <?php foreach ($chambres as $chambre): ?>
                                <option value="<?= htmlspecialchars($chambre['Numero']) ?>" data-id="<?= htmlspecialchars($chambre['IdChambre']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="DateEntree" class="form-label">Date d'entrée</label>
                        <input type="date" name="DateEntree" id="DateEntree" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="DateSortie" class="form-label">Date de sortie (Optionnel)</label>
                        <input type="date" name="DateSortie" id="DateSortie" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="MotifHospitalisation" class="form-label">Motif d'hospitalisation</label>
                        <textarea name="MotifHospitalisation" id="MotifHospitalisation" class="form-control" placeholder="Motif d'hospitalisation"></textarea>
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
function openHospitaliserModal(h) {
    const isEdit = h !== null;
    document.getElementById('IdHospitaliser').value = isEdit ? h.IdHospitaliser : '';
    document.getElementById('IdPatientInput').value = isEdit ? h.NomPatient : '';
    document.getElementById('IdChambreInput').value = isEdit ? h.NomChambre : '';
    document.getElementById('DateEntree').value = isEdit ? h.DateEntree : '';
    document.getElementById('DateSortie').value = isEdit && h.DateSortie !== 'Non spécifiée' ? h.DateSortie : '';
    document.getElementById('MotifHospitalisation').value = isEdit ? h.MotifHospitalisation : '';
    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Hospitalisation" : "Nouvelle Hospitalisation";
    new bootstrap.Modal(document.getElementById('hospitaliserModal')).show();
}

const patientInput = document.getElementById('IdPatientInput');
const patientIdInput = document.getElementById('IdPatient');
patientInput.addEventListener('input', function() {
    const option = Array.from(document.querySelectorAll('#patientOptions option')).find(opt => opt.value === patientInput.value);
    patientIdInput.value = option ? option.dataset.id : '';
});

const chambreInput = document.getElementById('IdChambreInput');
const chambreIdInput = document.getElementById('IdChambre');
chambreInput.addEventListener('input', function() {
    const option = Array.from(document.querySelectorAll('#chambreOptions option')).find(opt => opt.value === chambreInput.value);
    chambreIdInput.value = option ? option.dataset.id : '';
});

document.getElementById('hospitaliserForm').addEventListener('submit', function(e) {
    if(patientIdInput.value === '' || chambreIdInput.value === '') {
        e.preventDefault();
        alert('Veuillez sélectionner un patient et une chambre dans les listes.');
        patientIdInput.value === '' ? patientInput.focus() : chambreInput.focus();
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
