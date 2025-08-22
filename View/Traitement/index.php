<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/TraitementController.php';
require_once __DIR__ . '/../../controller/PatientController.php';
require_once __DIR__ . '/../../controller/MedecinController.php';

$title = "Traitements";
$pageTitle = "Traitements";

$db = (new Database())->getConnection();

$traitementController = new TraitementController($db);
$patientController = new PatientController($db);
$medecinController = new MedecinController($db);

$patients = $patientController->index();
$medecins = $medecinController->index();

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $traitementController->update($_POST['IdTraitement'], $_POST);
        header("Location: index.php?msg=modif");
        exit;
    } else {
        $traitementController->store($_POST);
        header("Location: index.php?msg=ajout");
        exit;
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $traitementController->destroy($_GET['delete']);
    header("Location: index.php?msg=suppr");
    exit;
}

$traitements = $traitementController->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#traitementModal" onclick="openTraitementModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Traitement ajouté ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Traitement modifié ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Traitement supprimé 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Patient</th>
                                <th>Médecin</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($traitements as $tr): ?>
                                <tr>
                                    <td><?= $tr['IdTraitement'] ?></td>
                                    <td><?= htmlspecialchars($tr['Description']) ?></td>
                                    <td><?= htmlspecialchars($tr['DateDebut']) ?></td>
                                    <td><?= htmlspecialchars($tr['DateFin']) ?></td>
                                    <td>
                                        <?php 
                                        $patient = array_filter($patients, fn($p) => $p['IdPatient'] == $tr['IdPatient']);
                                        $patient = array_shift($patient);
                                        echo $patient ? htmlspecialchars($patient['Nom'].' '.$patient['PostNom'].' '.$patient['Prenom']) : '';
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $med = array_filter($medecins, fn($m) => $m['IdMedecin'] == $tr['IdMedecin']);
                                        $med = array_shift($med);
                                        echo $med ? htmlspecialchars($med['Nom'].' '.$med['PostNom'].' '.$med['Prenom']) : '';
                                        ?>
                                    </td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openTraitementModal(<?= json_encode($tr) ?>)'>
                                            <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                        </a>
                                        <a class="text-danger mx-1" href="?delete=<?= $tr['IdTraitement'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                            <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Traitement -->
<div class="modal fade" id="traitementModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouveau Traitement</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="traitementForm" method="POST">
                    <input type="hidden" name="IdTraitement" id="IdTraitement">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <textarea name="Description" id="Description" class="form-control" placeholder="Description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="date" name="DateDebut" id="DateDebut" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <input type="date" name="DateFin" id="DateFin" class="form-control" required>
                    </div>

                    <!-- Patient avec datalist affichant le nom complet -->
                    <div class="mb-3">
                        <input list="patientsList" id="PatientNom" class="form-control" placeholder="Sélectionner un patient" required>
                        <input type="hidden" name="IdPatient" id="IdPatient">
                        <datalist id="patientsList">
                            <?php foreach ($patients as $p): ?>
                                <option data-id="<?= $p['IdPatient'] ?>" value="<?= htmlspecialchars($p['Nom'].' '.$p['PostNom'].' '.$p['Prenom']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <!-- Médecin avec datalist affichant le nom complet -->
                    <div class="mb-3">
                        <input list="medecinsList" id="MedecinNom" class="form-control" placeholder="Sélectionner un médecin" required>
                        <input type="hidden" name="IdMedecin" id="IdMedecin">
                        <datalist id="medecinsList">
                            <?php foreach ($medecins as $m): ?>
                                <option data-id="<?= $m['IdMedecin'] ?>" value="<?= htmlspecialchars($m['Nom'].' '.$m['PostNom'].' '.$m['Prenom']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
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
function openTraitementModal(tr) {
    const isEdit = tr !== null;
    document.getElementById('IdTraitement').value = isEdit ? tr.IdTraitement : '';
    document.getElementById('Description').value = isEdit ? tr.Description : '';
    document.getElementById('DateDebut').value = isEdit ? tr.DateDebut : '';
    document.getElementById('DateFin').value = isEdit ? tr.DateFin : '';

    // Remplir Patient
    if(isEdit) {
        const patientOption = Array.from(document.getElementById('patientsList').options)
                                   .find(opt => opt.dataset.id == tr.IdPatient);
        document.getElementById('PatientNom').value = patientOption ? patientOption.value : '';
        document.getElementById('IdPatient').value = tr.IdPatient;
    } else {
        document.getElementById('PatientNom').value = '';
        document.getElementById('IdPatient').value = '';
    }

    // Remplir Médecin
    if(isEdit) {
        const medOption = Array.from(document.getElementById('medecinsList').options)
                               .find(opt => opt.dataset.id == tr.IdMedecin);
        document.getElementById('MedecinNom').value = medOption ? medOption.value : '';
        document.getElementById('IdMedecin').value = tr.IdMedecin;
    } else {
        document.getElementById('MedecinNom').value = '';
        document.getElementById('IdMedecin').value = '';
    }

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Traitement" : "Nouveau Traitement";

    new bootstrap.Modal(document.getElementById('traitementModal')).show();
}

// Lors de la sélection dans la datalist, remplir le champ caché IdPatient et IdMedecin
document.getElementById('PatientNom').addEventListener('input', function() {
    const val = this.value;
    const option = Array.from(document.getElementById('patientsList').options)
                        .find(opt => opt.value === val);
    document.getElementById('IdPatient').value = option ? option.dataset.id : '';
});
document.getElementById('MedecinNom').addEventListener('input', function() {
    const val = this.value;
    const option = Array.from(document.getElementById('medecinsList').options)
                        .find(opt => opt.value === val);
    document.getElementById('IdMedecin').value = option ? option.dataset.id : '';
});

// Alert auto-dismiss
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
