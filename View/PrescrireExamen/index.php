<?php
/**
 * Fichier index pour la gestion des prescriptions d'examens.
 * Ce script gère les requêtes d'ajout, de modification et de suppression.
 */

session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/PrescrireExamenController.php';

$title = "Prescriptions d'Examens";
$pageTitle = "Prescriptions d'Examens";

// Crée une instance de la classe Database
$database = new Database();
// Récupère la connexion de type mysqli
$db = $database->getConnection();

// Récupérer l'ID du médecin connecté depuis la session (cela dépend de votre implémentation d'authentification)
$connectedMedecinId = $_SESSION['user_id'] ?? null; // Assurez-vous que c'est la bonne clé de session

// Crée une instance du contrôleur avec la connexion à la base de données
// S'assure que la connexion à la base de données est valide
if ($db) {
    $controller = new PrescrireExamenController($db);

    // Récupère les listes des patients et des examens pour les datalists
    $patients = $controller->getAllPatients();
    $examens = $controller->getAllExamens();

    // Gère les requêtes HTTP POST pour l'ajout et la modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        // Si la méthode est PUT (modification)
        if ($method === 'PUT') {
            // Appelle la méthode update du contrôleur avec l'ID et les données
            $controller->update($_POST['IdPrescrireExamen'], $_POST);
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
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#prescrireExamenModal" onclick="openPrescrireExamenModal(null)">
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
                                <th>Examen</th>
                                <th>Patient</th>
                                <th>Date Prescription</th>
                                <th>Commentaires</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($prescriptions)): ?>
                                <?php foreach ($prescriptions as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['IdPrescrireExamen']) ?></td>
                                        <td><?= htmlspecialchars($p['NomMedecin']) ?></td>
                                        <td><?= htmlspecialchars($p['NomExamen']) ?></td>
                                        <td><?= htmlspecialchars($p['NomPatient']) ?></td>
                                        <td><?= htmlspecialchars($p['DatePrescription']) ?></td>
                                        <td><?= htmlspecialchars($p['Commentaires']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openPrescrireExamenModal(<?= json_encode($p) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($p['IdPrescrireExamen']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Aucune prescription trouvée.</td>
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
<div class="modal fade" id="prescrireExamenModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Prescription</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="prescrireExamenForm" method="POST">
                    <input type="hidden" name="IdPrescrireExamen" id="IdPrescrireExamen">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    <!-- Champ caché pour l'ID du médecin, récupéré de la session -->
                    <input type="hidden" name="IdMedecin" id="IdMedecin" value="<?= htmlspecialchars($connectedMedecinId) ?>">

                    <div class="mb-3">
                        <label for="IdExamenInput" class="form-label">Examen</label>
                        <!-- Champ datalist pour les examens -->
                        <input class="form-control" list="examenOptions" id="IdExamenInput" name="IdExamen" placeholder="Rechercher ou sélectionner un examen..." required>
                        <datalist id="examenOptions">
                            <?php foreach ($examens as $examen): ?>
                                <option value="<?= htmlspecialchars($examen['NomExamen']) ?>" data-id="<?= htmlspecialchars($examen['IdExamen']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label for="IdPatientInput" class="form-label">Patient</label>
                        <!-- Champ datalist pour les patients -->
                        <input class="form-control" list="patientOptions" id="IdPatientInput" name="IdPatient" placeholder="Rechercher ou sélectionner un patient..." required>
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
                        <label for="Commentaires" class="form-label">Commentaires</label>
                        <textarea name="Commentaires" id="Commentaires" class="form-control" placeholder="Commentaires"></textarea>
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
    function openPrescrireExamenModal(p) {
        const isEdit = p !== null;
        document.getElementById('IdPrescrireExamen').value = isEdit ? p.IdPrescrireExamen : '';
        // Pour les datalists, le champ de saisie prend la valeur de l'ID.
        // Côté serveur, le NomExamen et le NomPatient ne sont pas des colonnes dans la table PrescrireExamen, mais les IdExamen et IdPatient le sont.
        // Donc, nous pouvons définir la valeur de l'entrée sur les Id, car c'est ce qui est envoyé au serveur.
        document.getElementById('IdExamenInput').value = isEdit ? p.NomExamen : '';
        document.getElementById('IdPatientInput').value = isEdit ? p.NomPatient : '';
        
        document.getElementById('DatePrescription').value = isEdit ? p.DatePrescription : '';
        document.getElementById('Commentaires').value = isEdit ? p.Commentaires : '';

        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Prescription" : "Nouvelle Prescription";

        new bootstrap.Modal(document.getElementById('prescrireExamenModal')).show();
    }
    
    // Fonction pour masquer automatiquement les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);

    // Fonction pour gérer la soumission du formulaire et envoyer les IDs
    document.getElementById('prescrireExamenForm').addEventListener('submit', function(event) {
        // Crée des champs cachés pour les IDs
        const examenName = document.getElementById('IdExamenInput').value;
        const patientName = document.getElementById('IdPatientInput').value;
        
        const examenId = document.querySelector(`#examenOptions option[value='${examenName}']`).dataset.id;
        const patientId = document.querySelector(`#patientOptions option[value='${patientName}']`).dataset.id;

        const examenInput = document.createElement('input');
        examenInput.type = 'hidden';
        examenInput.name = 'IdExamen';
        examenInput.value = examenId;

        const patientInput = document.createElement('input');
        patientInput.type = 'hidden';
        patientInput.name = 'IdPatient';
        patientInput.value = patientId;

        // Ajoute les champs au formulaire avant la soumission
        this.appendChild(examenInput);
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
