<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/InfirmierController.php';

$title = "Infirmiers";
$pageTitle = "Infirmiers";

$db = (new Database())->getConnection();
$controller = new InfirmierController($db);

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
        // Modification
        $controller->update($_POST['IdInfirmier'], $_POST);
        header("Location: index.php?controller=infirmier&action=index&msg=modif");
        exit;
    } elseif (isset($_POST['ajouter'])) {
        // Ajout
        $controller->store($_POST);
        header("Location: index.php?controller=infirmier&action=index&msg=ajout");
        exit;
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $controller->destroy($_GET['delete']);
    header("Location: index.php?controller=infirmier&action=index&msg=suppr");
    exit;
}

$infirmiers = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <!-- Liste des infirmiers -->
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#infirmierModal" onclick="openInfirmierModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'ajout'): ?>
                        <div class="alert alert-success">Infirmier ajouté avec succès ✅</div>
                    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'modif'): ?>
                        <div class="alert alert-info">Infirmier modifié avec succès ✏️</div>
                    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'suppr'): ?>
                        <div class="alert alert-danger">Infirmier supprimé avec succès 🗑️</div>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th type="hidden">#</th>
                                <th>Nom</th>
                                <th>Post-nom</th>
                                <th>Prénom</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Adresse</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($infirmiers as $inf): ?>
                                <tr>
                                    <td type="hidden"><?= $inf['IdInfirmier'] ?></td>
                                    <td><?= htmlspecialchars($inf['Nom']) ?></td>
                                    <td><?= htmlspecialchars($inf['PostNom']) ?></td>
                                    <td><?= htmlspecialchars($inf['Prenom']) ?></td>
                                    <td><?= htmlspecialchars($inf['Telephone']) ?></td>
                                    <td><?= htmlspecialchars($inf['Email']) ?></td>
                                    <td><?= htmlspecialchars($inf['Adresse']) ?></td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openInfirmierModal(<?= json_encode($inf) ?>)'>
                                            <span class="badge bg-success">
                                                <i class="bi bi-pencil-square fa-lg"></i>
                                            </span>
                                        </a>
                                        <a class="text-danger mx-1"
                                           href="index.php?controller=infirmier&action=index&delete=<?= $inf['IdInfirmier'] ?>"
                                           onclick="return confirm('Voulez-vous vraiment supprimer cet infirmier ?')">
                                            <span class="badge bg-danger">
                                                <i class="bi bi-trash fa-lg"></i>
                                            </span>
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

<!-- Modal Ajout / Modification Infirmier -->
<div class="modal fade" id="infirmierModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvel Infirmier</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="infirmierForm" method="POST">
                    <input type="hidden" name="IdInfirmier" id="IdInfirmier">
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
                        </div>

                        <!-- Colonne droite -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="text" name="Telephone" id="Telephone" class="form-control" placeholder="Téléphone">
                            </div>
                            <div class="mb-3">
                                <input type="email" name="Email" id="Email" class="form-control" placeholder="Email">
                            </div>
                            <div class="mb-3">
                                <input type="text" name="Adresse" id="Adresse" class="form-control" placeholder="Adresse">
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
function openInfirmierModal(inf) {
    const isEdit = inf !== null;
    document.getElementById('IdInfirmier').value = isEdit ? inf.IdInfirmier : '';
    document.getElementById('Nom').value = isEdit ? inf.Nom : '';
    document.getElementById('PostNom').value = isEdit ? inf.PostNom : '';
    document.getElementById('Prenom').value = isEdit ? inf.Prenom : '';
    document.getElementById('Telephone').value = isEdit ? inf.Telephone : '';
    document.getElementById('Email').value = isEdit ? inf.Email : '';
    document.getElementById('Adresse').value = isEdit ? inf.Adresse : '';

    const form = document.getElementById('infirmierForm');
    form.action = isEdit ? "index.php?controller=infirmier&action=index" : "";
    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";

    const modalTitle = document.getElementById('modalTitle');
    modalTitle.innerText = isEdit ? "Modifier Infirmier" : "Nouvel Infirmier";

    const modal = new bootstrap.Modal(document.getElementById('infirmierModal'));
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
