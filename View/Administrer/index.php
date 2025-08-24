<?php
/**
 * Fichier index pour la gestion des administrations.
 * Ce script gère l'affichage, l'ajout, la modification et la suppression.
 */

session_start();

// Assurez-vous que les chemins d'accès sont corrects.
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/Administrer.php';
require_once __DIR__ . '/../../model/Infirmier.php';
require_once __DIR__ . '/../../model/Traitement.php';

$title = "Administrations des Traitements";
$pageTitle = "Administration des Traitements";

/**
 * Le contrôleur gère la logique d'application, en faisant le lien
 * entre les modèles et la vue.
 */
class AdministrerController
{
    private $administrerModel;
    private $infirmierModel;
    private $traitementModel;

    public function __construct($db)
    {
        $this->administrerModel = new Administrer($db);
        $this->infirmierModel = new Infirmier($db);
        $this->traitementModel = new Traitement($db);
    }

    /**
     * Récupère la liste de toutes les administrations.
     * @return array
     */
    public function index()
    {
        return $this->administrerModel->getAll();
    }

    /**
     * Crée une nouvelle administration.
     * @param array $data Les données du formulaire.
     * @return bool
     */
    public function store($data)
    {
        return $this->administrerModel->create($data);
    }

    /**
     * Met à jour une administration existante.
     * @param int $id L'ID de l'administration.
     * @param array $data Les nouvelles données.
     * @return bool
     */
    public function update($id, $data)
    {
        return $this->administrerModel->update($id, $data);
    }

    /**
     * Supprime une administration.
     * @param int $id L'ID de l'administration à supprimer.
     * @return bool
     */
    public function delete($id)
    {
        return $this->administrerModel->delete($id);
    }

    /**
     * Récupère la liste de tous les infirmiers.
     * @return array
     */
    public function getAllInfirmiers()
    {
        return $this->infirmierModel->getAll();
    }

    /**
     * Récupère la liste de tous les traitements.
     * @return array
     */
    public function getAllTraitements()
    {
        return $this->traitementModel->getAll();
    }
}

// Initialisation de la connexion et du contrôleur
$database = new Database();
$db = $database->getConnection();
$controller = new AdministrerController($db);

// ID de l'infirmier connecté (simulé pour l'exemple)
// Dans une application réelle, vous le récupéreriez via la session de l'utilisateur.
// Par exemple: $_SESSION['IdInfirmier'] = 1;
$connectedInfirmierId = $_SESSION['IdInfirmier'] ?? null;

// Gestion des requêtes POST pour l'ajout ou la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
        $controller->update($_POST['IdAdministrer'], $_POST);
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
$administrations = $controller->index();
$infirmiers = $controller->getAllInfirmiers();
$traitements = $controller->getAllTraitements();

// Recherche de l'infirmier connecté dans la liste pour pré-remplir le champ
$connectedInfirmierName = '';
if ($connectedInfirmierId) {
    foreach ($infirmiers as $infirmier) {
        if ($infirmier['IdInfirmier'] == $connectedInfirmierId) {
            $connectedInfirmierName = $infirmier['NomInfirmier'] . ' ' . $infirmier['PrenomInfirmier'];
            break;
        }
    }
}

ob_start(); // Démarre la mise en tampon de la sortie
?>

<!-- Contenu HTML de la page -->
<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#administrerModal" onclick="openAdministrerModal()">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Administration ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Administration modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Administration supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Infirmier</th>
                                <th>Traitement</th>
                                <th>Date</th>
                                <th>Observations</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($administrations)): ?>
                                <?php foreach ($administrations as $a): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($a['IdAdministrer']) ?></td>
                                        <td><?= htmlspecialchars($a['IdInfirmier']) ?></td>
                                        <td><?= htmlspecialchars($a['IdTraitement']) ?></td>
                                        <td><?= htmlspecialchars($a['DateAdministration']) ?></td>
                                        <td><?= htmlspecialchars($a['Observations']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openAdministrerModal(<?= json_encode($a) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($a['IdAdministrer']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Aucune administration trouvée.</td>
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
<div class="modal fade" id="administrerModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Administration</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="administrerForm" method="POST">
                    <input type="hidden" name="IdAdministrer" id="IdAdministrer">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    
                    <!-- Le champ pour l'infirmier est maintenant complètement masqué.
                         Son ID est automatiquement pris de la session. -->
                    <input type="hidden" name="IdInfirmier" id="IdInfirmierInput" value="<?= htmlspecialchars($connectedInfirmierId) ?>">

                    <!-- Champ pour le traitement avec une datalist pour la recherche -->
                    <div class="mb-3">
                        <label for="TraitementInput" class="form-label">Traitement</label>
                        <input class="form-control" list="traitementOptions" id="TraitementInput" placeholder="Rechercher un traitement..." required>
                        <!-- Le champ caché pour soumettre l'ID correct -->
                        <input type="hidden" name="IdTraitement" id="IdTraitementHidden">
                        <datalist id="traitementOptions">
                            <?php foreach ($traitements as $traitement): ?>
                                <!-- Ajout de data-id pour lier l'option à l'ID -->
                                <option data-id="<?= htmlspecialchars($traitement['IdTraitement']) ?>" value="<?= htmlspecialchars($traitement['NomTraitement']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="mb-3">
                        <label for="DateAdministration" class="form-label">Date d'Administration</label>
                        <input type="date" name="DateAdministration" id="DateAdministration" class="form-control" required>
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
     * Ouvre le modal et pré-remplit les champs pour la modification ou l'ajout.
     * @param {object|null} a Les données de l'administration ou null pour une nouvelle.
     */
    function openAdministrerModal(a = null) {
        const isEdit = a !== null;
        document.getElementById('IdAdministrer').value = isEdit ? a.IdAdministrer : '';
        document.getElementById('DateAdministration').value = isEdit ? a.DateAdministration : '';
        document.getElementById('Observations').value = isEdit ? a.Observations : '';
        
        // Gère la liste de recherche pour les traitements
        const traitementInput = document.getElementById('TraitementInput');
        const traitementHiddenInput = document.getElementById('IdTraitementHidden');
        
        // Réinitialise les champs de recherche avant l'ouverture du modal
        traitementInput.value = '';
        traitementHiddenInput.value = '';

        if (isEdit) {
            // Pour le mode édition, trouve le nom du traitement basé sur l'ID de l'administration
            const selectedTraitementOption = document.querySelector(`#traitementOptions option[data-id="${a.IdTraitement}"]`);
            if (selectedTraitementOption) {
                traitementInput.value = selectedTraitementOption.value;
                traitementHiddenInput.value = a.IdTraitement;
            }
        }
        
        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Administration" : "Nouvelle Administration";
        
        // Écouteur d'événement pour mettre à jour le champ caché du traitement
        // Cet événement est important pour s'assurer que l'ID correct est envoyé.
        traitementInput.addEventListener('input', (event) => {
            const selectedOption = document.querySelector(`#traitementOptions option[value="${event.target.value}"]`);
            if (selectedOption) {
                traitementHiddenInput.value = selectedOption.getAttribute('data-id');
            } else {
                // Si l'utilisateur a tapé une valeur qui ne correspond pas, l'ID est effacé
                traitementHiddenInput.value = '';
            }
        });

        // Initialise et affiche le modal
        new bootstrap.Modal(document.getElementById('administrerModal')).show();
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
$content = ob_get_clean(); // Récupère le contenu et l'efface
include __DIR__ . '/../../templates/layout.php'; // Inclut le template de mise en page
?>
