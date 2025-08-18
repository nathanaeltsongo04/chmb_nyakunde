<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/PatientController.php';

$title = "Patients";
$pageTitle = "Patients";

$controller = new PatientController();

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
        $controller->update($_POST['IdPatient'], $_POST);
        header("Location: index.php?msg=modif");
        exit;
    } elseif (isset($_POST['ajouter'])) {
        $controller->store($_POST);
        header("Location: index.php?msg=ajout");
        exit;
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $controller->destroy($_GET['delete']);
    header("Location: index.php?msg=suppr");
    exit;
}

$patients = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#patientModal" onclick="openPatientModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <div class="alert alert-<?= $_GET['msg'] === 'ajout' ? 'success' : ($_GET['msg'] === 'modif' ? 'info' : 'danger') ?>">
                            Patient <?= $_GET['msg'] === 'ajout' ? 'ajouté' : ($_GET['msg'] === 'modif' ? 'modifié' : 'supprimé') ?> avec succès ✅
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive text-nowrap">
                        <table class="table datatable text-center">
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
                                <th>Email</th>
                                <th>Num Assurance</th>
                                <th>Groupe Sanguin</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $pat): ?>
                                <tr>
                                    <td><?= $pat['IdPatient'] ?></td>
                                    <td><?= htmlspecialchars($pat['Nom']) ?></td>
                                    <td><?= htmlspecialchars($pat['PostNom']) ?></td>
                                    <td><?= htmlspecialchars($pat['Prenom']) ?></td>
                                    <td><?= htmlspecialchars($pat['DateNaissance']) ?></td>
                                    <td><?= htmlspecialchars($pat['Sexe']) ?></td>
                                    <td><?= htmlspecialchars($pat['Adresse']) ?></td>
                                    <td><?= htmlspecialchars($pat['Telephone']) ?></td>
                                    <td><?= htmlspecialchars($pat['Email']) ?></td>
                                    <td><?= htmlspecialchars($pat['NumAssurance']) ?></td>
                                    <td><?= htmlspecialchars($pat['GroupeSanguin']) ?></td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openPatientModal(<?= json_encode($pat) ?>)'>
                                            <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                        </a>
                                        <a class="text-danger mx-1" href="index.php?delete=<?= $pat['IdPatient'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ce patient ?')">
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
</div>

<!-- Modal Patient -->
<div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouveau Patient</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="patientForm" method="POST">
                    <input type="hidden" name="IdPatient" id="IdPatient">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="row">
                        <!-- Colonne gauche -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="text" name="Nom" id="Nom" class="form-control" placeholder="Nom" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="PostNom" id="PostNom" class="form-control" placeholder="Post-nom" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="Prenom" id="Prenom" class="form-control" placeholder="Prénom" required>
                            </div>
                            <div class="mb-3">
                                <input type="date" name="DateNaissance" id="DateNaissance" class="form-control" placeholder="Date de naissance">
                            </div>
                            <div class="mb-3">
                                <select name="Sexe" id="Sexe" class="form-control">
                                    <option value="">Sélectionner le sexe</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                            </div>
                        </div>

                        <!-- Colonne droite -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="text" name="Adresse" id="Adresse" class="form-control" placeholder="Adresse">
                            </div>
                            <div class="mb-3">
                                <input type="text" name="Telephone" id="Telephone" class="form-control" placeholder="Téléphone">
                            </div>
                            <div class="mb-3">
                                <input type="email" name="Email" id="Email" class="form-control" placeholder="Email">
                            </div>
                            <div class="mb-3">
                                <input type="text" name="NumAssurance" id="NumAssurance" class="form-control" placeholder="Numéro Assurance">
                            </div>
                            <div class="mb-3">
                                <input type="text" name="GroupeSanguin" id="GroupeSanguin" class="form-control" placeholder="Groupe Sanguin">
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button id="submitBtn" class="btn btn-secondary w-50 fw-bold" type="submit" name="ajouter">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openPatientModal(pat) {
    const isEdit = pat !== null;
    document.getElementById('IdPatient').value = isEdit ? pat.IdPatient : '';
    document.getElementById('Nom').value = isEdit ? pat.Nom : '';
    document.getElementById('PostNom').value = isEdit ? pat.PostNom : '';
    document.getElementById('Prenom').value = isEdit ? pat.Prenom : '';
    document.getElementById('DateNaissance').value = isEdit ? pat.DateNaissance : '';
    document.getElementById('Sexe').value = isEdit ? pat.Sexe : '';
    document.getElementById('Adresse').value = isEdit ? pat.Adresse : '';
    document.getElementById('Telephone').value = isEdit ? pat.Telephone : '';
    document.getElementById('Email').value = isEdit ? pat.Email : '';
    document.getElementById('NumAssurance').value = isEdit ? pat.NumAssurance : '';
    document.getElementById('GroupeSanguin').value = isEdit ? pat.GroupeSanguin : '';

    const form = document.getElementById('patientForm');
    form.action = isEdit ? "index.php" : "";
    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";

    const modalTitle = document.getElementById('modalTitle');
    modalTitle.innerText = isEdit ? "Modifier Patient" : "Nouveau Patient";

    const modal = new bootstrap.Modal(document.getElementById('patientModal'));
    modal.show();
}

// Faire disparaître automatiquement les alertes après 3 secondes
setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.classList.add('fade', 'show');
        setTimeout(() => alert.remove(), 500);
    });
}, 2000);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
