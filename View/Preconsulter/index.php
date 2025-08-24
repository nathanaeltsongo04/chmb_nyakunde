<?php
/**
 * Fichier index pour la gestion des pré-consultations.
 * Ce script gère les requêtes d'ajout, de modification et de suppression.
 */

session_start();
// Assurez-vous d'avoir une connexion à la base de données et les contrôleurs/modèles nécessaires.
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/PreconsulterController.php';
require_once __DIR__ . '/../../model/Infirmier.php';
require_once __DIR__ . '/../../model/Patient.php';

$title = "Pré-consultations";
$pageTitle = "Pré-consultations";

// Crée une instance de la classe Database
$database = new Database();
// Récupère la connexion de type mysqli
$db = $database->getConnection();

// Crée une instance du contrôleur avec la connexion à la base de données
// S'assure que la connexion à la base de données est valide
if ($db) {
    $controller = new PreconsulterController($db);

    // Récupère les listes des patients et des infirmiers pour les datalists dans le formulaire.
    $patients = $controller->getAllPatients();
    $infirmiers = $controller->getAllInfirmiers();

    // Gère les requêtes HTTP POST pour l'ajout et la modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        // Si la méthode est PUT (modification)
        if ($method === 'PUT') {
            // Appelle la méthode update du contrôleur avec l'ID et les données
            $controller->update($_POST['IdPreconsulter'], $_POST);
            header("Location: index.php?msg=modif");
            exit;
        } else { // Si la méthode est POST (ajout)
            // Appelle la méthode store du contrôleur avec les données
            $controller->store($_POST);
            header("Location: index.php?msg=ajout");
            exit;
        }
    }

    // Gère les requêtes HTTP GET pour la suppression
    if (isset($_GET['delete'])) {
        // Appelle la méthode delete du contrôleur avec l'ID
        $controller->delete($_GET['delete']);
        header("Location: index.php?msg=suppr");
        exit;
    }

    // Récupère toutes les pré-consultations pour l'affichage
    $preconsultations = $controller->index();
} else {
    // Gère le cas où la connexion à la base de données a échoué
    die("Erreur de connexion à la base de données.");
}

ob_start();
?>

<!-- Contenu HTML de la page -->
<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#preconsulterModal" onclick="openPreconsulterModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Pré-consultation ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Pré-consultation modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Pré-consultation supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Infirmier</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Observations</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($preconsultations)): ?>
                                <?php foreach ($preconsultations as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['IdPreconsulter']) ?></td>
                                        <td><?= htmlspecialchars($p['IdInfirmier']) ?></td>
                                        <td><?= htmlspecialchars($p['IdPatient']) ?></td>
                                        <td><?= htmlspecialchars($p['Date']) ?></td>
                                        <td><?= htmlspecialchars($p['Observations']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openPreconsulterModal(<?= json_encode($p) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($p['IdPreconsulter']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Aucune pré-consultation trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour l'ajout et la modification -->
<div class="modal fade" id="preconsulterModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Pré-consultation</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="preconsulterForm" method="POST">
                    <input type="hidden" name="IdPreconsulter" id="IdPreconsulter">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <label for="IdInfirmierInput" class="form-label">Infirmier</label>
                        <select class="form-select" id="IdInfirmierInput" name="IdInfirmier" required>
                            <option value="">Sélectionner un infirmier</option>
                            <?php foreach ($infirmiers as $infirmier): ?>
                                <option value="<?= htmlspecialchars($infirmier['IdInfirmier']) ?>">
                                    <?= htmlspecialchars($infirmier['NomInfirmier'] . ' ' . $infirmier['PrenomInfirmier']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="IdPatientInput" class="form-label">Patient</label>
                        <select class="form-select" id="IdPatientInput" name="IdPatient" required>
                            <option value="">Sélectionner un patient</option>
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?= htmlspecialchars($patient['IdPatient']) ?>">
                                    <?= htmlspecialchars($patient['NomPatient'] . ' ' . $patient['PrenomPatient']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="Date" class="form-label">Date</label>
                        <input type="date" name="Date" id="Date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="Observations" class="form-label">Observations</label>
                        <textarea name="Observations" id="Observations" class="form-control" placeholder="Observations"></textarea>
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
    /**
     * Ouvre le modal de pré-consultation et pré-remplit les champs si une pré-consultation est fournie.
     * @param {object|null} p Les données de la pré-consultation ou null pour une nouvelle pré-consultation.
     */
    function openPreconsulterModal(p) {
        const isEdit = p !== null;
        document.getElementById('IdPreconsulter').value = isEdit ? p.IdPreconsulter : '';
        document.getElementById('IdInfirmierInput').value = isEdit ? p.IdInfirmier : '';
        document.getElementById('IdPatientInput').value = isEdit ? p.IdPatient : '';
        document.getElementById('Date').value = isEdit ? p.Date : '';
        document.getElementById('Observations').value = isEdit ? p.Observations : '';

        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Pré-consultation" : "Nouvelle Pré-consultation";
        
        new bootstrap.Modal(document.getElementById('preconsulterModal')).show();
    }
    
    // Fonction pour masquer automatiquement les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);
</script>

<?php
$content = ob_get_clean();
// Assurez-vous d'avoir un fichier de mise en page `layout.php` qui inclut le contenu.
include __DIR__ . '/../../templates/layout.php';
?>
