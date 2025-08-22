<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/MedecinController.php';

$title = "Médecins";
$pageTitle = "Liste des Médecins";

$controller = new MedecinController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        $controller->update($_POST['IdMedecin'], $_POST);
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

$medecins = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="card recent-sales overflow-auto">
        <div class="filter">
            <a class="icon" data-bs-toggle="modal" data-bs-target="#medecinModal" onclick="openMedecinModal(null)">
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
                        <th>Matricule</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($medecins as $med): ?>
                        <tr>
                            <td><?= $med['IdMedecin'] ?></td>
                            <td><?= htmlspecialchars($med['Nom']) ?></td>
                            <td><?= htmlspecialchars($med['PostNom']) ?></td>
                            <td><?= htmlspecialchars($med['Prenom']) ?></td>
                            <td><?= htmlspecialchars($med['Specialite']) ?></td>
                            <td><?= htmlspecialchars($med['Matricule']) ?></td>
                            <td>
                                <a class="text-info mx-1" href="#" onclick='openMedecinModal(<?= json_encode($med) ?>)'>
                                    <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                </a>
                                <a class="text-danger mx-1" href="?delete=<?= $med['IdMedecin'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
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

<!-- Modal Medecin -->
<div class="modal fade" id="medecinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="modalTitle">Nouveau Médecin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="medecinForm" method="POST">
                    <input type="hidden" name="IdMedecin" id="IdMedecin">
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
                        <input type="text" name="Specialite" id="Specialite" class="form-control" placeholder="Spécialité">
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
function openMedecinModal(med) {
    const isEdit = med !== null;
    document.getElementById('IdMedecin').value = isEdit ? med.IdMedecin : '';
    document.getElementById('Nom').value = isEdit ? med.Nom : '';
    document.getElementById('PostNom').value = isEdit ? med.PostNom : '';
    document.getElementById('Prenom').value = isEdit ? med.Prenom : '';
    document.getElementById('Specialite').value = isEdit ? med.Specialite : '';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Médecin" : "Nouveau Médecin";

    new bootstrap.Modal(document.getElementById('medecinModal')).show();
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
