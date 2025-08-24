<?php
/**
 * Fichier index pour la gestion des consultations.
 * Ce script gère les requêtes d'ajout, de modification et de suppression.
 */

session_start();
// Les chemins peuvent varier en fonction de votre structure de projet
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/ConsulterController.php';

$title = "Consultations";
$pageTitle = "Consultations";

// Crée une instance de la classe Database
$database = new Database();
// Récupère la connexion de type mysqli
$db = $database->getConnection();

// Récupérer l'ID du médecin connecté depuis la session (à adapter selon votre système d'authentification)
$connectedMedecinId = $_SESSION['user_id'] ?? null; 

// Crée une instance du contrôleur avec la connexion à la base de données
if ($db) {
    $controller = new ConsulterController($db);

    // Récupère les listes des médecins et des patients pour les datalists
    $medecins = $controller->getAllMedecins();
    $patients = $controller->getAllPatients();

    // Gère les requêtes HTTP POST pour l'ajout et la modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        // Si la méthode est PUT (modification)
        if ($method === 'PUT') {
            // Appelle la méthode update du contrôleur avec l'ID et les données
            $controller->update($_POST['IdConsulter'], $_POST);
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

    // Récupère toutes les consultations pour l'affichage
    $consultations = $controller->index();
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
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#consulterModal" onclick="openConsulterModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Consultation ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Consultation modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Consultation supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Médecin</th>
                                <th>Patient</th>
                                <th>Date Consultation</th>
                                <th>Signes Vitaux</th>
                                <th>Diagnostic</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($consultations)): ?>
                                <?php foreach ($consultations as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['IdConsulter']) ?></td>
                                        <td><?= htmlspecialchars($c['NomMedecin']) ?></td>
                                        <td><?= htmlspecialchars($c['NomPatient']) ?></td>
                                        <td><?= htmlspecialchars($c['DateConsultation']) ?></td>
                                        <td><?= htmlspecialchars($c['SignesVitaux']) ?></td>
                                        <td><?= htmlspecialchars($c['Diagnostic']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openConsulterModal(<?= json_encode($c) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($c['IdConsulter']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Aucune consultation trouvée.</td>
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
<div class="modal fade" id="consulterModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Consultation</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="consulterForm" method="POST">
                    <input type="hidden" name="IdConsulter" id="IdConsulter">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <!-- Champ caché pour l'ID du médecin, récupéré de la session -->
                    <input type="hidden" name="IdMedecin" id="IdMedecin" value="<?= htmlspecialchars($connectedMedecinId) ?>">

                    <!-- Le champ Médecin est masqué, car il est géré par l'ID de session -->
                    <div class="mb-3" style="display: none;">
                        <label for="IdMedecinInput" class="form-label">Médecin</label>
                        <!-- Champ datalist pour les médecins -->
                        <input class="form-control" list="medecinOptions" id="IdMedecinInput" name="IdMedecinInput" placeholder="Rechercher ou sélectionner un médecin..." required disabled>
                        <datalist id="medecinOptions">
                            <?php foreach ($medecins as $medecin): ?>
                                <option value="<?= htmlspecialchars($medecin['Nom']) ?>" data-id="<?= htmlspecialchars($medecin['IdMedecin']) ?>">
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
                        <label for="DateConsultation" class="form-label">Date de consultation</label>
                        <input type="date" name="DateConsultation" id="DateConsultation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="SignesVitaux" class="form-label">Signes vitaux</label>
                        <textarea name="SignesVitaux" id="SignesVitaux" class="form-control" placeholder="Signes vitaux"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="Diagnostic" class="form-label">Diagnostic</label>
                        <textarea name="Diagnostic" id="Diagnostic" class="form-control" placeholder="Diagnostic"></textarea>
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
     * Ouvre le modal de consultation et pré-remplit les champs si une consultation est fournie.
     * @param {object|null} c Les données de la consultation ou null pour une nouvelle consultation.
     */
    function openConsulterModal(c) {
        const isEdit = c !== null;
        document.getElementById('IdConsulter').value = isEdit ? c.IdConsulter : '';
        document.getElementById('IdMedecinInput').value = isEdit ? c.NomMedecin : '';
        document.getElementById('IdPatientInput').value = isEdit ? c.NomPatient : '';
        
        document.getElementById('DateConsultation').value = isEdit ? c.DateConsultation : '';
        document.getElementById('SignesVitaux').value = isEdit ? c.SignesVitaux : '';
        document.getElementById('Diagnostic').value = isEdit ? c.Diagnostic : '';

        // Le champ médecin est visible et activé uniquement en mode édition
        document.getElementById('IdMedecinInput').parentElement.style.display = isEdit ? 'block' : 'none';

        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Consultation" : "Nouvelle Consultation";

        new bootstrap.Modal(document.getElementById('consulterModal')).show();
    }
    
    // Fonction pour masquer automatiquement les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);

    // Fonction pour gérer la soumission du formulaire et envoyer les IDs
    document.getElementById('consulterForm').addEventListener('submit', function(event) {
        // Crée des champs cachés pour les IDs
        const patientName = document.getElementById('IdPatientInput').value;
        
        const patientId = document.querySelector(`#patientOptions option[value='${patientName}']`).dataset.id;

        const patientInput = document.createElement('input');
        patientInput.type = 'hidden';
        patientInput.name = 'IdPatient';
        patientInput.value = patientId;

        // Ajoute le champ au formulaire avant la soumission
        this.appendChild(patientInput);

        // Retire le champ d'entrée original pour éviter d'envoyer le nom au lieu de l'ID
        document.getElementById('IdPatientInput').name = '';
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
