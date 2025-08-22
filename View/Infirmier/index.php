<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/InfirmierController.php';

$title = "Médecins";
$pageTitle = "Gestion des Médecins";

$controller = new InfirmierController();

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $controller->update($_POST['IdInfirmier'], $_POST);
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

$Infirmiers = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#InfirmierModal" onclick="openInfirmierModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Médecin ajouté ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Médecin modifié ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Médecin supprimé 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>PostNom</th>
                                <th>Prénom</th>
                                <th>Spécialité</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Adresse</th>
                                <th>NumLicence</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($Infirmiers as $m): ?>
                                <tr>
                                    <td><?= $m['IdInfirmier'] ?></td>
                                    <td><?= htmlspecialchars($m['Nom']) ?></td>
                                    <td><?= htmlspecialchars($m['PostNom']) ?></td>
                                    <td><?= htmlspecialchars($m['Prenom']) ?></td>
                                    <td><?= htmlspecialchars($m['Specialite']) ?></td>
                                    <td><?= htmlspecialchars($m['Telephone']) ?></td>
                                    <td><?= htmlspecialchars($m['Email']) ?></td>
                                    <td><?= htmlspecialchars($m['Adresse']) ?></td>
                                    <td><?= htmlspecialchars($m['NumLicence']) ?></td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openInfirmierModal(<?= json_encode($m) ?>)'>
                                            <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                        </a>
                                        <a class="text-danger mx-1" href="?delete=<?= $m['IdInfirmier'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
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

<!-- Modal Médecin -->
<div class="modal fade" id="InfirmierModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouveau Médecin</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="InfirmierForm" method="POST">
                    <input type="hidden" name="IdInfirmier" id="IdInfirmier">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3"><input type="text" name="Nom" id="Nom" class="form-control" placeholder="Nom" required></div>
                    <div class="mb-3"><input type="text" name="PostNom" id="PostNom" class="form-control" placeholder="PostNom" required></div>
                    <div class="mb-3"><input type="text" name="Prenom" id="Prenom" class="form-control" placeholder="Prénom" required></div>
                    <div class="mb-3"><input type="text" name="Specialite" id="Specialite" class="form-control" placeholder="Spécialité"></div>
                    <div class="mb-3"><input type="text" name="Telephone" id="Telephone" class="form-control" placeholder="Téléphone"></div>
                    <div class="mb-3"><input type="email" name="Email" id="Email" class="form-control" placeholder="Email"></div>
                    <div class="mb-3"><input type="text" name="Adresse" id="Adresse" class="form-control" placeholder="Adresse"></div>
                    <div class="mb-3"><input type="text" name="NumLicence" id="NumLicence" class="form-control" placeholder="Numéro Licence"></div>

                    <div class="text-center mt-3">
                        <button id="submitBtn" class="btn btn-secondary w-50 fw-bold" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openInfirmierModal(m) {
    const isEdit = m !== null;
    document.getElementById('IdInfirmier').value = isEdit ? m.IdInfirmier : '';
    document.getElementById('Nom').value = isEdit ? m.Nom : '';
    document.getElementById('PostNom').value = isEdit ? m.PostNom : '';
    document.getElementById('Prenom').value = isEdit ? m.Prenom : '';
    document.getElementById('Specialite').value = isEdit ? m.Specialite : '';
    document.getElementById('Telephone').value = isEdit ? m.Telephone : '';
    document.getElementById('Email').value = isEdit ? m.Email : '';
    document.getElementById('Adresse').value = isEdit ? m.Adresse : '';
    document.getElementById('NumLicence').value = isEdit ? m.NumLicence : '';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Médecin" : "Nouveau Médecin";

    new bootstrap.Modal(document.getElementById('InfirmierModal')).show();
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
