<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/MedicamentController.php';

$title = "Médicaments";
$pageTitle = "Médicaments";

$controller = new MedicamentController();

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $controller->update($_POST['IdMedicament'], $_POST);
        header("Location: index.php?msg=modif");
        exit;
    } else {
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

$medicaments = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#medicamentModal" onclick="openMedicamentModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Médicament ajouté ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Médicament modifié ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Médicament supprimé 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Dosage</th>
                                <th>Effets Secondaires</th>
                                <th>Prix</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medicaments as $med): ?>
                                <tr>
                                    <td><?= $med['IdMedicament'] ?></td>
                                    <td><?= htmlspecialchars($med['NomMedicament']) ?></td>
                                    <td><?= htmlspecialchars($med['Description']) ?></td>
                                    <td><?= htmlspecialchars($med['DosageStandard']) ?></td>
                                    <td><?= htmlspecialchars($med['EffetsSecondaires']) ?></td>
                                    <td><?= htmlspecialchars($med['Prix']) ?></td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openMedicamentModal(<?= json_encode($med) ?>)'>
                                            <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                        </a>
                                        <a class="text-danger mx-1" href="?delete=<?= $med['IdMedicament'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
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

<!-- Modal Médicament -->
<div class="modal fade" id="medicamentModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouveau Médicament</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="medicamentForm" method="POST">
                    <input type="hidden" name="IdMedicament" id="IdMedicament">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <input type="text" name="NomMedicament" id="NomMedicament" class="form-control" placeholder="Nom" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="Description" id="Description" class="form-control" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="DosageStandard" id="DosageStandard" class="form-control" placeholder="Dosage">
                    </div>
                    <div class="mb-3">
                        <textarea name="EffetsSecondaires" id="EffetsSecondaires" class="form-control" placeholder="Effets secondaires"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="number" step="0.01" name="Prix" id="Prix" class="form-control" placeholder="Prix">
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
function openMedicamentModal(med) {
    const isEdit = med !== null;
    document.getElementById('IdMedicament').value = isEdit ? med.IdMedicament : '';
    document.getElementById('NomMedicament').value = isEdit ? med.NomMedicament : '';
    document.getElementById('Description').value = isEdit ? med.Description : '';
    document.getElementById('DosageStandard').value = isEdit ? med.DosageStandard : '';
    document.getElementById('EffetsSecondaires').value = isEdit ? med.EffetsSecondaires : '';
    document.getElementById('Prix').value = isEdit ? med.Prix : '';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";

    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Médicament" : "Nouveau Médicament";

    new bootstrap.Modal(document.getElementById('medicamentModal')).show();
}

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
