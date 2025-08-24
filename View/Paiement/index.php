<?php
/**
 * Fichier index pour la gestion des paiements.
 * Ce script gère les requêtes d'ajout, de modification et de suppression.
 */

session_start();
// Les chemins peuvent varier en fonction de votre structure de projet
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../controller/PaiementController.php';

$title = "Paiements";
$pageTitle = "Gestion des Paiements";

// Crée une instance de la classe Database
$database = new Database();
// Récupère la connexion de type mysqli
$db = $database->getConnection();

// Crée une instance du contrôleur des paiements
if ($db) {
    $controller = new PaiementController($db);

    // Récupère la liste des patients pour les datalists
    // NOTE : Assurez-vous que votre PaiementController a bien une méthode getAllPatients()
    $patients = $controller->getAllPatients();

    // Gère les requêtes HTTP POST pour l'ajout et la modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = $_POST['_method'] ?? 'POST';
        
        // Si la méthode est PUT (modification)
        if ($method === 'PUT') {
            // Appelle la méthode update du contrôleur avec l'ID et les données
            $controller->update($_POST['IdPaiement'], $_POST);
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

    // Récupère tous les paiements pour l'affichage
    $paiements = $controller->index();
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
                    <!-- Bouton pour ouvrir le modal d'ajout -->
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#paiementModal" onclick="openPaiementModal(null)">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Paiement ajouté ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Paiement modifié ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Paiement supprimé 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Montant</th>
                                <th>Date Paiement</th>
                                <th>Mode de Paiement</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($paiements)): ?>
                                <?php foreach ($paiements as $p): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['IdPaiement']) ?></td>
                                        <td><?= htmlspecialchars($p['NomPatient']) ?></td>
                                        <td><?= htmlspecialchars($p['Montant']) ?></td>
                                        <td><?= htmlspecialchars($p['DatePaiement']) ?></td>
                                        <td><?= htmlspecialchars($p['ModePaiement']) ?></td>
                                        <td>
                                            <!-- Bouton pour ouvrir le modal de modification -->
                                            <a class="text-info mx-1" href="#" onclick='openPaiementModal(<?= json_encode($p) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <!-- Bouton pour la suppression -->
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($p['IdPaiement']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Aucun paiement trouvé.</td>
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
<div class="modal fade" id="paiementModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouveau Paiement</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paiementForm" method="POST">
                    <input type="hidden" name="IdPaiement" id="IdPaiement">
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
                        <label for="Montant" class="form-label">Montant</label>
                        <input type="number" step="0.01" name="Montant" id="Montant" class="form-control" placeholder="Montant du paiement" required min="0">
                    </div>
                    <div class="mb-3">
                        <label for="DatePaiement" class="form-label">Date de paiement</label>
                        <input type="date" name="DatePaiement" id="DatePaiement" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="ModePaiement" class="form-label">Mode de Paiement</label>
                        <select name="ModePaiement" id="ModePaiement" class="form-control" required>
                            <option value="Espèces">Espèces</option>
                            <option value="Carte bancaire">Carte bancaire</option>
                            <option value="Virement">Virement</option>
                            <option value="Mobile money">Mobile money</option>
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
    /**
     * Ouvre le modal de paiement et pré-remplit les champs si un paiement est fourni.
     * @param {object|null} p Les données du paiement ou null pour un nouveau paiement.
     */
    function openPaiementModal(p) {
        const isEdit = p !== null;
        document.getElementById('IdPaiement').value = isEdit ? p.IdPaiement : '';
        document.getElementById('IdPatientInput').value = isEdit ? p.NomPatient : '';
        document.getElementById('Montant').value = isEdit ? p.Montant : '';
        document.getElementById('DatePaiement').value = isEdit ? p.DatePaiement : '';
        document.getElementById('ModePaiement').value = isEdit ? p.ModePaiement : 'Espèces';

        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Paiement" : "Nouveau Paiement";

        new bootstrap.Modal(document.getElementById('paiementModal')).show();
    }
    
    // Fonction pour masquer automatiquement les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);

    // Fonction pour gérer la soumission du formulaire et envoyer les IDs
    document.getElementById('paiementForm').addEventListener('submit', function(event) {
        // Crée un champ caché pour l'ID du patient
        const patientName = document.getElementById('IdPatientInput').value;
        
        const patientOption = document.querySelector(`#patientOptions option[value='${patientName}']`);
        if (patientOption) {
            const patientId = patientOption.dataset.id;
            const patientInput = document.createElement('input');
            patientInput.type = 'hidden';
            patientInput.name = 'IdPatient';
            patientInput.value = patientId;
            this.appendChild(patientInput);
        }

        // Retire le champ d'entrée original pour éviter d'envoyer le nom au lieu de l'ID
        document.getElementById('IdPatientInput').name = '';
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
