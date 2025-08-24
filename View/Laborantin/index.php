<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/LaborantinController.php';

$title = "Laborantins";
$pageTitle = "Liste des Laborantins";

$controller = new LaborantinController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $controller->update($_POST['IdLaborantin'], $_POST);
        header("Location: index.php?msg=modif");
        exit;
    } else {
        $matricule = $controller->store($_POST);
        header("Location: index.php?msg=ajout");
        exit;
    }
}

if (isset($_GET['delete'])) {
    $controller->destroy($_GET['delete']);
    header("Location: index.php?msg=suppr");
    exit;
}

$laborantins = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="card recent-sales overflow-auto">
        <div class="filter">
            <a class="icon" data-bs-toggle="modal" data-bs-target="#laborantinModal" onclick="openLaborantinModal(null)">
                <i class="bi bi-plus-circle-fill h4"></i>
            </a>
        </div>

        <div class="card-body">
            <h5 class="card-title"><?= $pageTitle ?></h5>

            <?php if (isset($_GET['msg'])): ?>
                <?php if ($_GET['msg'] === 'ajout'): ?>
                    <div class="alert alert-success">Laborantin ajouté ✅</div>
                <?php elseif ($_GET['msg'] === 'modif'): ?>
                    <div class="alert alert-info">Laborantin modifié ✏️</div>
                <?php elseif ($_GET['msg'] === 'suppr'): ?>
                    <div class="alert alert-danger">Laborantin supprimé 🗑️</div>
                <?php endif; ?>
            <?php endif; ?>

            <table class="table datatable text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>PostNom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laborantins as $lab): ?>
                        <tr>
                            <td><?= $lab['IdLaborantin'] ?></td>
                            <td><?= htmlspecialchars($lab['Matricule']) ?></td>
                            <td><?= htmlspecialchars($lab['Nom']) ?></td>
                            <td><?= htmlspecialchars($lab['PostNom']) ?></td>
                            <td><?= htmlspecialchars($lab['Prenom']) ?></td>
                            <td><?= htmlspecialchars($lab['Telephone']) ?></td>
                            <td><?= htmlspecialchars($lab['Email']) ?></td>
                            <td><?= htmlspecialchars($lab['Adresse']) ?></td>
                            <td>
                                <a class="text-info mx-1" href="#" onclick='openLaborantinModal(<?= json_encode($lab) ?>)'>
                                    <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                </a>
                                <a class="text-danger mx-1" href="?delete=<?= $lab['IdLaborantin'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
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

<!-- Modal Laborantin -->
<div class="modal fade" id="laborantinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="modalTitle">Nouveau Laborantin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="laborantinForm" method="POST">
                    <input type="hidden" name="IdLaborantin" id="IdLaborantin">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    
                    <div class="mb-3">
                        <input type="text" name="Nom" id="Nom" class="form-control" placeholder="Nom" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="PostNom" id="PostNom" class="form-control" placeholder="PostNom" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="Prenom" id="Prenom" class="form-control" placeholder="Prénom" required>
                    </div>
                    <div class="mb-3">
                        <input type="tel" name="Telephone" id="Telephone" class="form-control" placeholder="Téléphone">
                    </div>
                    <div class="mb-3">
                        <input type="email" name="Email" id="Email" class="form-control" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="Adresse" id="Adresse" class="form-control" placeholder="Adresse">
                    </div>
                    
                    <div class="text-center mt-3">
                        <button id="submitBtn" class="btn btn-secondary w-50" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openLaborantinModal(lab) {
    const isEdit = lab !== null;
    document.getElementById('IdLaborantin').value = isEdit ? lab.IdLaborantin : '';
    document.getElementById('Nom').value = isEdit ? lab.Nom : '';
    document.getElementById('PostNom').value = isEdit ? lab.PostNom : '';
    document.getElementById('Prenom').value = isEdit ? lab.Prenom : '';
    document.getElementById('Telephone').value = isEdit ? lab.Telephone : '';
    document.getElementById('Email').value = isEdit ? lab.Email : '';
    document.getElementById('Adresse').value = isEdit ? lab.Adresse : '';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Laborantin" : "Nouveau Laborantin";

    new bootstrap.Modal(document.getElementById('laborantinModal')).show();
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