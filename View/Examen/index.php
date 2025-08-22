<?php

session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/ExamenController.php';

$title = "Examens";
$pageTitle = "Examens";

$controller = new ExamenController();

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $controller->update($_POST['IdExamen'], $_POST);
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

$examens = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#examenModal" onclick="openExamenModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Examen ajouté ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Examen modifié ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Examen supprimé 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Coût</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($examens as $ex): ?>
                                <tr>
                                    <td><?= $ex['IdExamen'] ?></td>
                                    <td><?= htmlspecialchars($ex['NomExamen']) ?></td>
                                    <td><?= htmlspecialchars($ex['Description']) ?></td>
                                    <td><?= htmlspecialchars($ex['Cout']) ?></td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openExamenModal(<?= json_encode($ex) ?>)'>
                                            <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                        </a>
                                        <a class="text-danger mx-1" href="?delete=<?= $ex['IdExamen'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
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

<!-- Modal Examen -->
<div class="modal fade" id="examenModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvel Examen</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="examenForm" method="POST">
                    <input type="hidden" name="IdExamen" id="IdExamen">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <input type="text" name="NomExamen" id="NomExamen" class="form-control" placeholder="Nom de l'examen" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="Description" id="Description" class="form-control" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="number" step="0.01" name="Cout" id="Cout" class="form-control" placeholder="Coût">
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
function openExamenModal(ex) {
    const isEdit = ex !== null;
    document.getElementById('IdExamen').value = isEdit ? ex.IdExamen : '';
    document.getElementById('NomExamen').value = isEdit ? ex.NomExamen : '';
    document.getElementById('Description').value = isEdit ? ex.Description : '';
    document.getElementById('Cout').value = isEdit ? ex.Cout : '';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Examen" : "Nouvel Examen";

    new bootstrap.Modal(document.getElementById('examenModal')).show();
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
