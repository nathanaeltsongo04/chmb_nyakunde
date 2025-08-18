<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/MedecinController.php';

$title = "Médecins";
$pageTitle = "Médecins";

$db = (new Database())->getConnection();
$controller = new MedecinController($db);

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
        // Modification
        $controller->update($_POST['IdMedecin'], $_POST);
        header("Location: index.php?controller=medecin&action=index&msg=modif");
        exit;
    } elseif (isset($_POST['ajouter'])) {
        // Ajout
        $controller->store($_POST);
        header("Location: index.php?controller=medecin&action=index&msg=ajout");
        exit;
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $controller->destroy($_GET['delete']);
    header("Location: index.php?controller=medecin&action=index&msg=suppr");
    exit;
}

$medecins = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <!-- Liste des médecins -->
        <div class="col-12">
            <div class="card recent-sales overflow-auto">

                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#medecinModal" onclick="openMedecinModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'ajout'): ?>
                        <div class="alert alert-success">Médecin ajouté avec succès ✅</div>
                    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'modif'): ?>
                        <div class="alert alert-info">Médecin modifié avec succès ✏️</div>
                    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'suppr'): ?>
                        <div class="alert alert-danger">Médecin supprimé avec succès 🗑️</div>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th type="hidden">#</th>
                                <th>Nom</th>
                                <th>Post-nom</th>
                                <th>Prénom</th>
                                <th>Spécialité</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Adresse</th>
                                <th>Licence</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medecins as $med): ?>
                                <tr>
                                    <td type="hidden"><?= $med['IdMedecin'] ?></td>
                                    <td><?= htmlspecialchars($med['Nom']) ?></td>
                                    <td><?= htmlspecialchars($med['PostNom']) ?></td>
                                    <td><?= htmlspecialchars($med['Prenom']) ?></td>
                                    <td><?= htmlspecialchars($med['Specialite']) ?></td>
                                    <td><?= htmlspecialchars($med['Telephone']) ?></td>
                                    <td><?= htmlspecialchars($med['Email']) ?></td>
                                    <td><?= htmlspecialchars($med['Adresse']) ?></td>
                                    <td><?= htmlspecialchars($med['NumLicence']) ?></td>
                                    <td>
                                        <a class="text-info mx-1" href="#" onclick='openMedecinModal(<?= json_encode($med) ?>)'>
                                            <span class="badge bg-success">
                                                <i class="bi bi-pencil-square fa-lg"></i>
                                            </span>
                                        </a>
                                        <a class="text-danger mx-1"
                                           href="index.php?controller=medecin&action=index&delete=<?= $med['IdMedecin'] ?>"
                                           onclick="return confirm('Voulez-vous vraiment supprimer ce médecin ?')">
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

<!-- Modal Ajout / Modification Médecin -->
<div class="modal fade" id="medecinModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouveau Médecin</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="medecinForm" method="POST">
                    <input type="hidden" name="IdMedecin" id="IdMedecin">
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
                                <input type="text" name="Specialite" id="Specialite" class="form-control" placeholder="Spécialité">
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
                            <div class="mb-3">
                                <input type="text" name="NumLicence" id="NumLicence" class="form-control" placeholder="Numéro Licence">
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
function openMedecinModal(med) {
    const isEdit = med !== null;
    document.getElementById('IdMedecin').value = isEdit ? med.IdMedecin : '';
    document.getElementById('Nom').value = isEdit ? med.Nom : '';
    document.getElementById('PostNom').value = isEdit ? med.PostNom : '';
    document.getElementById('Prenom').value = isEdit ? med.Prenom : '';
    document.getElementById('Specialite').value = isEdit ? med.Specialite : '';
    document.getElementById('Telephone').value = isEdit ? med.Telephone : '';
    document.getElementById('Email').value = isEdit ? med.Email : '';
    document.getElementById('Adresse').value = isEdit ? med.Adresse : '';
    document.getElementById('NumLicence').value = isEdit ? med.NumLicence : '';

    const form = document.getElementById('medecinForm');
    form.action = isEdit ? "index.php?controller=medecin&action=index" : "";
    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";

    const modalTitle = document.getElementById('modalTitle');
    modalTitle.innerText = isEdit ? "Modifier Médecin" : "Nouveau Médecin";

    const modal = new bootstrap.Modal(document.getElementById('medecinModal'));
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
