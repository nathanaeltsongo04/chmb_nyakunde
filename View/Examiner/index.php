<?php
/**
 * Fichier index pour la gestion des examens.
 * Ce script gère les requêtes d'ajout, de modification et de suppression.
 */

session_start();
// Les chemins peuvent varier en fonction de votre structure de projet
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/ExaminerController.php'; // Nouveau contrôleur pour les examens
require_once __DIR__ . '/../../controller/ExamenController.php'; // Pour récupérer la liste des examens

$title = "Examens Laboratoire";
$pageTitle = "Examens Laboratoire";

// Crée une instance de la classe Database
$database = new Database();
// Récupère la connexion de type mysqli
$db = $database->getConnection();

// Récupérer l'ID du laborantin connecté depuis la session (à adapter selon votre système d'authentification)
$connectedLaborantinId = $_SESSION['user_id'] ?? null; 

// Crée des instances des contrôleurs
if ($db) {
    $controller = new ExaminerController($db);
    $examenController = new ExamenController($db);
    
    // Récupère les listes des examens et des patients pour les datalists
    $examens = $examenController->index();
    $patients = $controller->getAllPatients(); 

    // Gère les requêtes HTTP POST pour l'ajout et la modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        // Si la méthode est PUT (modification)
        if ($method === 'PUT') {
            // Appelle la méthode update du contrôleur avec l'ID et les données
            $controller->update($_POST['IdExaminer'], $_POST);
            header("Location: index.php?msg=modif");
            exit;
        } else { // Si la méthode est POST (ajout)
            // Assigner l'ID du laborantin connecté aux données POST avant de les envoyer au contrôleur
            $_POST['IdLaborantin'] = $connectedLaborantinId;
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

    // Récupère toutes les entrées pour l'affichage
    $examinations = $controller->index();
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
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#examinerModal" onclick="openExaminerModal(null)">
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
                                <th>Laborantin</th>
                                <th>Examen</th>
                                <th>Patient</th>
                                <th>Date Examen</th>
                                <th>Résultat</th>
                                <th>Remarques</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($examinations)): ?>
                                <?php foreach ($examinations as $e): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($e['IdExaminer']) ?></td>
                                        <td><?= htmlspecialchars($e['NomLaborantin']) ?></td>
                                        <td><?= htmlspecialchars($e['NomExamen']) ?></td>
                                        <td><?= htmlspecialchars($e['NomPatient']) ?></td>
                                        <td><?= htmlspecialchars($e['DateExamen']) ?></td>
                                        <td><?= htmlspecialchars($e['Resultat']) ?></td>
                                        <td><?= htmlspecialchars($e['Remarques']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openExaminerModal(<?= json_encode($e) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($e['IdExaminer']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">Aucun examen trouvé.</td>
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
<div class="modal fade" id="examinerModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvel Examen</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="examinerForm" method="POST">
                    <input type="hidden" name="IdExaminer" id="IdExaminer">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <!-- Champ caché pour l'ID du laborantin, récupéré de la session -->
                    <input type="hidden" name="IdLaborantin" id="IdLaborantin" value="<?= htmlspecialchars($connectedLaborantinId) ?>">

                    <div class="mb-3">
                        <label for="IdExamenInput" class="form-label">Examen</label>
                        <!-- Champ datalist pour les examens -->
                        <input class="form-control" list="examenOptions" id="IdExamenInput" name="IdExamenInput" placeholder="Rechercher ou sélectionner un examen..." required>
                        <datalist id="examenOptions">
                            <?php foreach ($examens as $examen): ?>
                                <option value="<?= htmlspecialchars($examen['NomExamen']) ?>" data-id="<?= htmlspecialchars($examen['IdExamen']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label for="IdPatientInput" class="form-label">Patient</label>
                        <!-- Champ datalist pour les patients -->
                        <input class="form-control" list="patientOptions" id="IdPatientInput" name="IdPatientInput" placeholder="Rechercher ou sélectionner un patient..." required>
                        <datalist id="patientOptions">
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?= htmlspecialchars($patient['Nom']) ?>" data-id="<?= htmlspecialchars($patient['IdPatient']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label for="DateExamen" class="form-label">Date Examen</label>
                        <input type="date" name="DateExamen" id="DateExamen" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Resultat" class="form-label">Résultat</label>
                        <textarea name="Resultat" id="Resultat" class="form-control" placeholder="Résultat"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="Remarques" class="form-label">Remarques</label>
                        <textarea name="Remarques" id="Remarques" class="form-control" placeholder="Remarques"></textarea>
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
     * Ouvre le modal de l'examen et pré-remplit les champs si un examen est fourni.
     * @param {object|null} e Les données de l'examen ou null pour un nouvel examen.
     */
    function openExaminerModal(e) {
        const isEdit = e !== null;
        document.getElementById('IdExaminer').value = isEdit ? e.IdExaminer : '';
        document.getElementById('IdExamenInput').value = isEdit ? e.NomExamen : '';
        document.getElementById('IdPatientInput').value = isEdit ? e.NomPatient : '';
        
        document.getElementById('DateExamen').value = isEdit ? e.DateExamen : '';
        document.getElementById('Resultat').value = isEdit ? e.Resultat : '';
        document.getElementById('Remarques').value = isEdit ? e.Remarques : '';

        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Examen" : "Nouvel Examen";

        new bootstrap.Modal(document.getElementById('examinerModal')).show();
    }
    
    // Fonction pour masquer automatiquement les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);

    // Fonction pour gérer la soumission du formulaire et envoyer les IDs
    document.getElementById('examinerForm').addEventListener('submit', function(event) {
        // Crée des champs cachés pour les IDs
        const examenName = document.getElementById('IdExamenInput').value;
        const patientName = document.getElementById('IdPatientInput').value;
        
        const examenId = document.querySelector(`#examenOptions option[value='${examenName}']`).dataset.id;
        const patientId = document.querySelector(`#patientOptions option[value='${patientName}']`).dataset.id;
        
        const examenInput = document.createElement('input');
        examenInput.type = 'hidden';
        examenInput.name = 'IdExamen';
        examenInput.value = examenId;
        this.appendChild(examenInput);

        const patientInput = document.createElement('input');
        patientInput.type = 'hidden';
        patientInput.name = 'IdPatient';
        patientInput.value = patientId;
        this.appendChild(patientInput);

        // Retire les champs d'entrée originaux pour éviter d'envoyer les noms au lieu des IDs
        document.getElementById('IdExamenInput').name = '';
        document.getElementById('IdPatientInput').name = '';
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
