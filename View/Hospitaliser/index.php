<?php
/**
 * Fichier index pour la gestion des hospitalisations.
 * Ce script gère les requêtes d'ajout, de modification et de suppression.
 */

session_start();
// Les chemins peuvent varier en fonction de votre structure de projet
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/HospitaliserController.php'; // Nouveau contrôleur pour les hospitalisations
require_once __DIR__ . '/../../controller/ChambreController.php'; // Pour récupérer la liste des chambres

$title = "Hospitalisations";
$pageTitle = "Hospitalisations";

// Crée une instance de la classe Database
$database = new Database();
// Récupère la connexion de type mysqli
$db = $database->getConnection();

// Crée des instances des contrôleurs
if ($db) {
    $controller = new HospitaliserController($db);
    $chambreController = new ChambreController($db);
    
    // Récupère les listes des chambres et des patients pour les datalists
    $chambres = $chambreController->index();
    $patients = $controller->getAllPatients(); 

    // Gère les requêtes HTTP POST pour l'ajout et la modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        // Si la méthode est PUT (modification)
        if ($method === 'PUT') {
            // Appelle la méthode update du contrôleur avec l'ID et les données
            $controller->update($_POST['IdHospitaliser'], $_POST);
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

    // Récupère toutes les entrées pour l'affichage
    $hospitalisations = $controller->index();
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
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#hospitaliserModal" onclick="openHospitaliserModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Hospitalisation ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Hospitalisation modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Hospitalisation supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Chambre</th>
                                <th>Date d'entrée</th>
                                <th>Date de sortie</th>
                                <th>Motif</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($hospitalisations)): ?>
                                <?php foreach ($hospitalisations as $h): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($h['IdHospitaliser']) ?></td>
                                        <td><?= htmlspecialchars($h['NomPatient']) ?></td>
                                        <td><?= htmlspecialchars($h['NomChambre']) ?></td>
                                        <td><?= htmlspecialchars($h['DateEntree']) ?></td>
                                        <td><?= htmlspecialchars($h['DateSortie'] ?? 'Non spécifiée') ?></td>
                                        <td><?= htmlspecialchars($h['MotifHospitalisation']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openHospitaliserModal(<?= json_encode($h) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($h['IdHospitaliser']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">Aucune hospitalisation trouvée.</td>
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
<div class="modal fade" id="hospitaliserModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Hospitalisation</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="hospitaliserForm" method="POST">
                    <input type="hidden" name="IdHospitaliser" id="IdHospitaliser">
                    <input type="hidden" name="_method" id="_method" value="POST">

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
                        <label for="IdChambreInput" class="form-label">Chambre</label>
                        <!-- Champ datalist pour les chambres -->
                        <input class="form-control" list="chambreOptions" id="IdChambreInput" name="IdChambreInput" placeholder="Rechercher ou sélectionner une chambre..." required>
                        <datalist id="chambreOptions">
                            <?php foreach ($chambres as $chambre): ?>
                                <option value="<?= htmlspecialchars($chambre['Numero']) ?>" data-id="<?= htmlspecialchars($chambre['IdChambre']) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label for="DateEntree" class="form-label">Date d'entrée</label>
                        <input type="date" name="DateEntree" id="DateEntree" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="DateSortie" class="form-label">Date de sortie (Optionnel)</label>
                        <input type="date" name="DateSortie" id="DateSortie" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="MotifHospitalisation" class="form-label">Motif d'hospitalisation</label>
                        <textarea name="MotifHospitalisation" id="MotifHospitalisation" class="form-control" placeholder="Motif d'hospitalisation"></textarea>
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
     * Ouvre le modal de l'hospitalisation et pré-remplit les champs si une entrée est fournie.
     * @param {object|null} h Les données de l'hospitalisation ou null pour une nouvelle entrée.
     */
    function openHospitaliserModal(h) {
        const isEdit = h !== null;
        document.getElementById('IdHospitaliser').value = isEdit ? h.IdHospitaliser : '';
        document.getElementById('IdPatientInput').value = isEdit ? h.NomPatient : '';
        document.getElementById('IdChambreInput').value = isEdit ? h.NomChambre : '';
        
        document.getElementById('DateEntree').value = isEdit ? h.DateEntree : '';
        document.getElementById('DateSortie').value = isEdit && h.DateSortie !== 'Non spécifiée' ? h.DateSortie : '';
        document.getElementById('MotifHospitalisation').value = isEdit ? h.MotifHospitalisation : '';

        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Hospitalisation" : "Nouvelle Hospitalisation";

        new bootstrap.Modal(document.getElementById('hospitaliserModal')).show();
    }
    
    // Fonction pour masquer automatiquement les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);

    // Fonction pour gérer la soumission du formulaire et envoyer les IDs
    document.getElementById('hospitaliserForm').addEventListener('submit', function(event) {
        // Crée des champs cachés pour les IDs
        const patientName = document.getElementById('IdPatientInput').value;
        const chambreName = document.getElementById('IdChambreInput').value;
        
        const patientId = document.querySelector(`#patientOptions option[value='${patientName}']`).dataset.id;
        const chambreId = document.querySelector(`#chambreOptions option[value='${chambreName}']`).dataset.id;
        
        const patientInput = document.createElement('input');
        patientInput.type = 'hidden';
        patientInput.name = 'IdPatient';
        patientInput.value = patientId;
        this.appendChild(patientInput);

        const chambreInput = document.createElement('input');
        chambreInput.type = 'hidden';
        chambreInput.name = 'IdChambre';
        chambreInput.value = chambreId;
        this.appendChild(chambreInput);

        // Retire les champs d'entrée originaux pour éviter d'envoyer les noms au lieu des IDs
        document.getElementById('IdPatientInput').name = '';
        document.getElementById('IdChambreInput').name = '';
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
