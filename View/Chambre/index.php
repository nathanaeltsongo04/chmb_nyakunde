<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/ChambreController.php';

$title = "Chambres";
$pageTitle = "Chambres";

$controller = new ChambreController();

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $controller->update($_POST['IdChambre'], $_POST);
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

$chambres = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#chambreModal" onclick="openChambreModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Chambre ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Chambre modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Chambre supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Numéro</th>
                                <th>Type</th>
                                <th>État</th>
                                <th>Prix par jour</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chambres as $ch): ?>
                                <tr>
                                    <td><?= $ch['IdChambre'] ?></td>
                                    <td><?= htmlspecialchars($ch['Numero']) ?></td>
                                    <td><?= htmlspecialchars($ch['Type']) ?></td>
                                    <td><?= htmlspecialchars($ch['Etat']) ?></td>
                                    <td><?= htmlspecialchars($ch['PrixParJour']) ?></td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openChambreModal(<?= json_encode($ch) ?>)'>
                                            <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                        </a>
                                        <a class="text-danger mx-1" href="?delete=<?= $ch['IdChambre'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
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

<!-- Modal Chambre -->
<div class="modal fade" id="chambreModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Chambre</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="chambreForm" method="POST">
                    <input type="hidden" name="IdChambre" id="IdChambre">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <input type="text" name="Numero" id="Numero" class="form-control" placeholder="Numéro" required>
                    </div>
                    <div class="mb-3">
                        <select name="Type" id="Type" class="form-control">
                            <option value="simple">Simple</option>
                            <option value="double">Double</option>
                            <option value="suite">Suite</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <select name="Etat" id="Etat" class="form-control">
                            <option value="disponible">Disponible</option>
                            <option value="occupee">Occupée</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="number" step="0.01" name="PrixParJour" id="PrixParJour" class="form-control" placeholder="Prix par jour">
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
function openChambreModal(ch) {
    const isEdit = ch !== null;
    document.getElementById('IdChambre').value = isEdit ? ch.IdChambre : '';
    document.getElementById('Numero').value = isEdit ? ch.Numero : '';
    document.getElementById('Type').value = isEdit ? ch.Type : 'simple';
    document.getElementById('Etat').value = isEdit ? ch.Etat : 'disponible';
    document.getElementById('PrixParJour').value = isEdit ? ch.PrixParJour : '';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Chambre" : "Nouvelle Chambre";

    new bootstrap.Modal(document.getElementById('chambreModal')).show();
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
