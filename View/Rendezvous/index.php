<?php
session_start();
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/RendezVousController.php';
require_once __DIR__ . '/../../controller/PatientController.php';
require_once __DIR__ . '/../../controller/MedecinController.php';

$title = "Rendez-vous";
$pageTitle = "Rendez-vous";

// Connexion à la base
$database = new Database();
$conn = $database->getConnection(); // mysqli

// Vérification si utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    die("Utilisateur non connecté. Veuillez vous connecter.");
}

// Instanciation des contrôleurs avec mysqli
$rendezVousController = new RendezVousController($conn);
$patientController = new PatientController($conn);
$medecinController = new MedecinController($conn);

// Liste des patients
$patients = $patientController->index();

// Ajout ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    $_POST['IdMedecin'] = $_SESSION['user_id']; // ID du médecin connecté

    if ($method === 'PUT') {
        $rendezVousController->update($_POST['IdRendezVous'], $_POST);
        header("Location: index.php?msg=modif");
        exit;
    } else {
        $rendezVousController->store($_POST);
        header("Location: index.php?msg=ajout");
        exit;
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $rendezVousController->destroy($_GET['delete']);
    header("Location: index.php?msg=suppr");
    exit;
}

// Liste des rendez-vous
$rendezvous = $rendezVousController->index();

ob_start();
?>

<div class="col-lg-12">
    <div class="card recent-sales overflow-auto">
        <div class="card-body d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title"><?= $pageTitle ?></h5>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#rendezVousModal" onclick="openRendezVousModal(null)">
                <i class="bi bi-plus-circle-fill me-2"></i>Nouveau Rendez-vous
            </button>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-<?= $_GET['msg'] === 'ajout' ? 'success' : ($_GET['msg'] === 'modif' ? 'info' : 'danger') ?>">
                <?= $_GET['msg'] === 'ajout' ? 'Rendez-vous ajouté ✅' : ($_GET['msg'] === 'modif' ? 'Rendez-vous modifié ✏️' : 'Rendez-vous supprimé 🗑️') ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive mt-3">
            <table class="table datatable text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date & Heure</th>
                        <th>Patient</th>
                        <th>Médecin</th>
                        <th>Objet</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rendezvous as $rv): ?>
                        <tr>
                            <td><?= $rv['IdRendezVous'] ?></td>
                            <td><?= htmlspecialchars($rv['DateHeure']) ?></td>
                            <td>
                                <?php
                                $patient = array_filter($patients, fn($p) => $p['IdPatient'] == $rv['IdPatient']);
                                $patient = array_shift($patient);
                                echo $patient ? htmlspecialchars($patient['Nom'].' '.$patient['PostNom'].' '.$patient['Prenom']) : '';
                                ?>
                            </td>
                            <td><?= htmlspecialchars($_SESSION['nom_complet']) ?></td>
                            <td><?= htmlspecialchars($rv['Objet']) ?></td>
                            <td><?= htmlspecialchars($rv['Statut']) ?></td>
                            <td>
                                <a class="text-info mx-1" href="#" onclick='openRendezVousModal(<?= json_encode($rv) ?>)'>
                                    <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                </a>
                                <a class="text-danger mx-1" href="?delete=<?= $rv['IdRendezVous'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
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

<!-- Modal CRUD -->
<div class="modal fade" id="rendezVousModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouveau Rendez-vous</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rendezVousForm" method="POST">
                    <input type="hidden" name="IdRendezVous" id="IdRendezVous">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <input type="hidden" name="IdMedecin" value="<?= $_SESSION['user_id'] ?>">

                    <div class="mb-3">
                        <input type="datetime-local" name="DateHeure" id="DateHeure" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <input list="patientsList" name="IdPatient" id="IdPatient" class="form-control" placeholder="Sélectionner un patient" required>
                        <datalist id="patientsList">
                            <?php foreach ($patients as $p): ?>
                                <option value="<?= $p['IdPatient'] ?>"><?= htmlspecialchars($p['Nom'].' '.$p['PostNom'].' '.$p['Prenom']) ?></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="Objet" id="Objet" class="form-control" placeholder="Objet">
                    </div>

                    <div class="mb-3">
                        <select name="Statut" id="Statut" class="form-control">
                            <option value="en_attente">En attente</option>
                            <option value="confirme">Confirmé</option>
                            <option value="annule">Annulé</option>
                        </select>
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
function openRendezVousModal(rv) {
    const isEdit = rv !== null;
    document.getElementById('IdRendezVous').value = isEdit ? rv.IdRendezVous : '';
    document.getElementById('DateHeure').value = isEdit ? rv.DateHeure.replace(' ', 'T') : '';
    document.getElementById('IdPatient').value = isEdit ? rv.IdPatient : '';
    document.getElementById('Objet').value = isEdit ? rv.Objet : '';
    document.getElementById('Statut').value = isEdit ? rv.Statut : 'en_attente';

    document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
    document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
    document.getElementById('modalTitle').innerText = isEdit ? "Modifier Rendez-vous" : "Nouveau Rendez-vous";

    new bootstrap.Modal(document.getElementById('rendezVousModal')).show();
}

// Alert auto-dismiss
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(alert => {
        alert.classList.add('fade', 'show');
        setTimeout(() => alert.remove(), 5000);
    });
}, 2000);
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
