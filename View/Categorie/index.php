<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/CategorieController.php';

$title = "Catégories";
$pageTitle = "Catégories";

$controller = new CategorieController();

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $controller->update($_POST['IdCategorie'], $_POST);
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

$categories = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#categorieModal" onclick="openCategorieModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Catégorie ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Catégorie modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Catégorie supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><?= $cat['IdCategorie'] ?></td>
                                    <td><?= htmlspecialchars($cat['NomCategorie']) ?></td>
                                    <td><?= htmlspecialchars($cat['Description']) ?></td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openCategorieModal(<?= json_encode($cat) ?>)'>
                                            <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                        </a>
                                        <a class="text-danger mx-1" href="?delete=<?= $cat['IdCategorie'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
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

<!-- Modal Catégorie -->
<div class="modal fade" id="categorieModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Catégorie</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="categorieForm" method="POST">
                    <input type="hidden" name="IdCategorie" id="IdCategorie">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <input type="text" name="NomCategorie" id="NomCategorie" class="form-control" placeholder="Nom" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="Description" id="Description" class="form-control" placeholder="Description"></textarea>
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
function openCategorieModal(cat) {
    const isEdit = cat !== null;
    document.getElementById('IdCategorie').value = isEdit ? cat.IdCategorie : '';
    document.getElementById('NomCategorie').value = isEdit ? cat.NomCategorie : '';
    document.getElementById('Description').value = isEdit ? cat.Description : '';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Catégorie" : "Nouvelle Catégorie";

    new bootstrap.Modal(document.getElementById('categorieModal')).show();
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
