<?php
/**
 * Fichier index pour la gestion de l'association Effectuer (Paiement-Patient).
 * Ce script gère l'affichage, l'ajout, la modification et la suppression.
 */

session_start();

// Assurez-vous que les chemins d'accès sont corrects.
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/Effectuer.php';
require_once __DIR__ . '/../../model/Paiement.php';
require_once __DIR__ . '/../../model/Patient.php';

$title = "Gestion des Transactions (Effectuer)";
$pageTitle = "Transactions (Paiements & Patients)";

/**
 * Le contrôleur gère la logique d'application, en faisant le lien
 * entre les modèles et la vue.
 */
class EffectuerController
{
    private $effectuerModel;
    private $paiementModel;
    private $patientModel;

    public function __construct($db)
    {
        $this->effectuerModel = new Effectuer($db);
        $this->paiementModel = new Paiement($db);
        $this->patientModel = new Patient($db);
    }

    /**
     * Récupère la liste de toutes les transactions Effectuer.
     * @return array
     */
    public function index()
    {
        return $this->effectuerModel->getAll();
    }

    /**
     * Crée une nouvelle transaction.
     * @param array $data Les données du formulaire.
     * @return bool
     */
    public function store($data)
    {
        return $this->effectuerModel->create($data);
    }

    /**
     * Met à jour une transaction existante.
     * @param int $id L'ID de la transaction.
     * @param array $data Les nouvelles données.
     * @return bool
     */
    public function update($id, $data)
    {
        return $this->effectuerModel->update($id, $data);
    }

    /**
     * Supprime une transaction.
     * @param int $id L'ID de la transaction à supprimer.
     * @return bool
     */
    public function delete($id)
    {
        return $this->effectuerModel->delete($id);
    }

    /**
     * Récupère la liste de tous les paiements.
     * @return array
     */
    public function getAllPaiements()
    {
        return $this->paiementModel->getAll();
    }

    /**
     * Récupère la liste de tous les patients.
     * @return array
     */
    public function getAllPatients()
    {
        return $this->patientModel->getAll();
    }
}

// Initialisation de la connexion et du contrôleur
$database = new Database();
$db = $database->getConnection();
$controller = new EffectuerController($db);

// Gestion des requêtes POST pour l'ajout ou la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
        $controller->update($_POST['IdEffectuer'], $_POST);
        header("Location: index.php?msg=modif");
    } else {
        $controller->store($_POST);
        header("Location: index.php?msg=ajout");
    }
    exit;
}

// Gestion de la requête GET pour la suppression
if (isset($_GET['delete'])) {
    $controller->delete($_GET['delete']);
    header("Location: index.php?msg=suppr");
    exit;
}

// Récupération des données pour l'affichage
$transactions = $controller->index();
$paiements = $controller->getAllPaiements();
$patients = $controller->getAllPatients();

ob_start(); // Démarre la mise en tampon de la sortie
?>

<!-- Contenu HTML de la page -->
<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#effectuerModal" onclick="openEffectuerModal()">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Transaction ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Transaction modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Transaction supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Paiement</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions)): ?>
                                <?php foreach ($transactions as $t): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($t['IdEffectuer']) ?></td>
                                        <td><?= htmlspecialchars($t['IdPaiement']) ?></td>
                                        <td><?= htmlspecialchars($t['IdPatient']) ?></td>
                                        <td><?= htmlspecialchars($t['DateEffectuation']) ?></td>
                                        <td><?= htmlspecialchars($t['Montant']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openEffectuerModal(<?= json_encode($t) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($t['IdEffectuer']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Aucune transaction trouvée.</td>
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
<div class="modal fade" id="effectuerModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Transaction</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="effectuerForm" method="POST">
                    <input type="hidden" name="IdEffectuer" id="IdEffectuer">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    
                    <div class="mb-3">
                        <label for="IdPaiementInput" class="form-label">Paiement</label>
                        <input type="text" class="form-control" id="IdPaiementInput" list="paiementsList" placeholder="Rechercher un paiement..." required>
                        <datalist id="paiementsList">
                            <?php foreach ($paiements as $paiement): ?>
                                <option value="<?= htmlspecialchars($paiement['IdPaiement']) ?>" data-id="<?= htmlspecialchars($paiement['IdPaiement']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <input type="hidden" name="IdPaiement" id="IdPaiement">
                    </div>

                    <div class="mb-3">
                        <label for="IdPatientInput" class="form-label">Patient</label>
                        <input type="text" class="form-control" id="IdPatientInput" list="patientsList" placeholder="Rechercher un patient..." required>
                        <datalist id="patientsList">
                            <?php foreach ($patients as $patient): ?>
                                <option value="<?= htmlspecialchars($patient['IdPatient']) ?>" data-id="<?= htmlspecialchars($patient['IdPatient']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <input type="hidden" name="IdPatient" id="IdPatient">
                    </div>

                    <div class="mb-3">
                        <label for="DateEffectuation" class="form-label">Date de la transaction</label>
                        <input type="date" class="form-control" name="DateEffectuation" id="DateEffectuation" required>
                    </div>

                    <div class="mb-3">
                        <label for="Montant" class="form-label">Montant</label>
                        <input type="number" step="0.01" class="form-control" name="Montant" id="Montant" required>
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
     * Ouvre le modal et pré-remplit les champs pour la modification ou l'ajout.
     * @param {object|null} t Les données de la transaction ou null pour une nouvelle.
     */
    function openEffectuerModal(t = null) {
        const isEdit = t !== null;
        document.getElementById('IdEffectuer').value = isEdit ? t.IdEffectuer : '';
        document.getElementById('DateEffectuation').value = isEdit ? t.DateEffectuation : '';
        document.getElementById('Montant').value = isEdit ? t.Montant : '';
        
        // Gère les champs datalist pour l'édition
        if (isEdit) {
            const paiementOption = document.querySelector(`#paiementsList option[data-id="${t.IdPaiement}"]`);
            document.getElementById('IdPaiementInput').value = paiementOption ? paiementOption.value : '';
            document.getElementById('IdPaiement').value = t.IdPaiement;

            const patientOption = document.querySelector(`#patientsList option[data-id="${t.IdPatient}"]`);
            document.getElementById('IdPatientInput').value = patientOption ? patientOption.value : '';
            document.getElementById('IdPatient').value = t.IdPatient;
        } else {
            document.getElementById('IdPaiementInput').value = '';
            document.getElementById('IdPaiement').value = '';
            document.getElementById('IdPatientInput').value = '';
            document.getElementById('IdPatient').value = '';
        }
        
        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Transaction" : "Nouvelle Transaction";
        
        new bootstrap.Modal(document.getElementById('effectuerModal')).show();
    }
    
    // Événement pour lier la valeur de l'input caché à l'ID de la datalist
    document.getElementById('IdPaiementInput').addEventListener('input', function() {
        const value = this.value;
        const option = document.querySelector(`#paiementsList option[value="${value}"]`);
        document.getElementById('IdPaiement').value = option ? option.dataset.id : '';
    });

    document.getElementById('IdPatientInput').addEventListener('input', function() {
        const value = this.value;
        const option = document.querySelector(`#patientsList option[value="${value}"]`);
        document.getElementById('IdPatient').value = option ? option.dataset.id : '';
    });
    
    // Fonction pour masquer automatiquement les alertes
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);
</script>

<?php
$content = ob_get_clean(); // Récupère le contenu et l'efface
include __DIR__ . '/../../templates/layout.php'; // Inclut le template de mise en page
?>
