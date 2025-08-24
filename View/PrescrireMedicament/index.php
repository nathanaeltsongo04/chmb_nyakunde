<?php
/**
 * Fichier index pour la gestion des prescriptions.
 * Ce script gère les requêtes d'ajout, de modification et de suppression.
 */

session_start();
// Les chemins peuvent varier en fonction de votre structure de projet
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/PrescrireMedicamentController.php'; // Nouveau contrôleur pour les prescriptions
require_once __DIR__ . '/../../controller/MedicamentController.php'; // Pour récupérer la liste des médicaments

$title = "Prescriptions";
$pageTitle = "Prescriptions";

// Crée une instance de la classe Database
$database = new Database();
// Récupère la connexion de type mysqli
$db = $database->getConnection();

// Récupérer l'ID du médecin connecté depuis la session (à adapter selon votre système d'authentification)
$connectedMedecinId = $_SESSION['user_id'] ?? null; 

// Crée des instances des contrôleurs
if ($db) {
    $controller = new PrescrireController($db);
    $medicamentController = new MedicamentController($db);
    
    // Récupère les listes des médicaments et des patients pour les datalists
    $medicaments = $medicamentController->index();
    $patients = $controller->getAllPatients(); // Assumant que le PrescrireController a cette méthode

    // Gère les requêtes HTTP POST pour l'ajout et la modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        // Si la méthode est PUT (modification)
        if ($method === 'PUT') {
            // Appelle la méthode update du contrôleur avec l'ID et les données
            $controller->update($_POST['IdPrescrireMedicament'], $_POST);
            header("Location: index.php?msg=modif");
            exit;
        } else { // Si la méthode est POST (ajout)
            // Assigner l'ID du médecin connecté aux données POST avant de les envoyer au contrôleur
            $_POST['IdMedecin'] = $connectedMedecinId;
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

    // Récupère toutes les prescriptions pour l'affichage
    $prescriptions = $controller->index();
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
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#prescrireModal" onclick="openPrescrireModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Prescription ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Prescription modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Prescription supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Médecin</th>
                                <th>Médicament</th>
                                <th>Patient</th>
                                <th>Date Prescription</th>
                                <th>Dosage</th>
                                <th>Durée</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($prescriptions)): ?>
                                <?php foreach ($prescriptions as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['IdPrescrireMedicament']) ?></td>
                                        <td><?= htmlspecialchars($p['NomMedecin']) ?></td>
                                        <td><?= htmlspecialchars($p['NomMedicament']) ?></td>
                                        <td><?= htmlspecialchars($p['NomPatient']) ?></td>
                                        <td><?= htmlspecialchars($p['DatePrescription']) ?></td>
                                        <td><?= htmlspecialchars($p['Dosage']) ?></td>
                                        <td><?= htmlspecialchars($p['Duree']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openPrescrireModal(<?= json_encode($p) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($p['IdPrescrireMedicament']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">Aucune prescription trouvée.</td>
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
<div class="modal fade" id="prescrireModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Prescription</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="prescrireForm" method="POST">
                    <input type="hidden" name="IdPrescrireMedicament" id="IdPrescrireMedicament">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <!-- Champ caché pour l'ID du médecin, récupéré de la session -->
                    <input type="hidden" name="IdMedecin" id="IdMedecin" value="<?= htmlspecialchars($connectedMedecinId) ?>">

                    <div class="mb-3">
                        <label for="IdMedicamentInput" class="form-label">Médicament</label>
                        <!-- Champ datalist pour les médicaments -->
                        <input class="form-control" list="medicamentOptions" id="IdMedicamentInput" name="IdMedicamentInput" placeholder="Rechercher ou sélectionner un médicament..." required>
                        <datalist id="medicamentOptions">
                            <?php foreach ($medicaments as $medicament): ?>
                                <option value="<?= htmlspecialchars($medicament['NomMedicament']) ?>" data-id="<?= htmlspecialchars($medicament['IdMedicament']) ?>">
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
                        <label for="DatePrescription" class="form-label">Date de prescription</label>
                        <input type="date" name="DatePrescription" id="DatePrescription" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Dosage" class="form-label">Dosage</label>
                        <input type="text" name="Dosage" id="Dosage" class="form-control" placeholder="Dosage">
                    </div>
                    <div class="mb-3">
                        <label for="Duree" class="form-label">Durée</label>
                        <input type="text" name="Duree" id="Duree" class="form-control" placeholder="Durée">
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
     * Ouvre le modal de prescription et pré-remplit les champs si une prescription est fournie.
     * @param {object|null} p Les données de la prescription ou null pour une nouvelle prescription.
     */
    function openPrescrireModal(p) {
        const isEdit = p !== null;
        document.getElementById('IdPrescrireMedicament').value = isEdit ? p.IdPrescrireMedicament : '';
        document.getElementById('IdMedicamentInput').value = isEdit ? p.NomMedicament : '';
        document.getElementById('IdPatientInput').value = isEdit ? p.NomPatient : '';
        
        document.getElementById('DatePrescription').value = isEdit ? p.DatePrescription : '';
        document.getElementById('Dosage').value = isEdit ? p.Dosage : '';
        document.getElementById('Duree').value = isEdit ? p.Duree : '';

        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Prescription" : "Nouvelle Prescription";

        new bootstrap.Modal(document.getElementById('prescrireModal')).show();
    }
    
    // Fonction pour masquer automatiquement les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);

    // Fonction pour gérer la soumission du formulaire et envoyer les IDs
    document.getElementById('prescrireForm').addEventListener('submit', function(event) {
        // Crée des champs cachés pour les IDs
        const medicamentName = document.getElementById('IdMedicamentInput').value;
        const patientName = document.getElementById('IdPatientInput').value;
        
        const medicamentId = document.querySelector(`#medicamentOptions option[value='${medicamentName}']`).dataset.id;
        const patientId = document.querySelector(`#patientOptions option[value='${patientName}']`).dataset.id;
        
        const medicamentInput = document.createElement('input');
        medicamentInput.type = 'hidden';
        medicamentInput.name = 'IdMedicament';
        medicamentInput.value = medicamentId;
        this.appendChild(medicamentInput);

        const patientInput = document.createElement('input');
        patientInput.type = 'hidden';
        patientInput.name = 'IdPatient';
        patientInput.value = patientId;
        this.appendChild(patientInput);

        // Retire les champs d'entrée originaux pour éviter d'envoyer les noms au lieu des IDs
        document.getElementById('IdMedicamentInput').name = '';
        document.getElementById('IdPatientInput').name = '';
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
