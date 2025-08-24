<?php
/**
 * Fichier index pour la gestion des relations Concerner (Catégorie-Médicament).
 * Ce script gère l'affichage, l'ajout, la modification et la suppression.
 */

session_start();

// Assurez-vous que les chemins d'accès sont corrects.
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../model/Concerner.php';
require_once __DIR__ . '/../../model/Categorie.php';
require_once __DIR__ . '/../../model/Medicament.php';

$title = "Relations Catégories-Médicaments";
$pageTitle = "Relations Catégories-Médicaments";

/**
 * Le contrôleur gère la logique d'application, en faisant le lien
 * entre les modèles et la vue.
 */
class ConcernerController
{
    private $concernerModel;
    private $categorieModel;
    private $medicamentModel;

    public function __construct($db)
    {
        $this->concernerModel = new Concerner($db);
        $this->categorieModel = new Categorie($db);
        $this->medicamentModel = new Medicament($db);
    }

    /**
     * Récupère la liste de toutes les relations Concerner.
     * @return array
     */
    public function index()
    {
        return $this->concernerModel->getAll();
    }

    /**
     * Crée une nouvelle relation.
     * @param array $data Les données du formulaire.
     * @return bool
     */
    public function store($data)
    {
        return $this->concernerModel->create($data);
    }

    /**
     * Met à jour une relation existante.
     * @param int $id L'ID de la relation.
     * @param array $data Les nouvelles données.
     * @return bool
     */
    public function update($id, $data)
    {
        return $this->concernerModel->update($id, $data);
    }

    /**
     * Supprime une relation.
     * @param int $id L'ID de la relation à supprimer.
     * @return bool
     */
    public function delete($id)
    {
        return $this->concernerModel->delete($id);
    }

    /**
     * Récupère la liste de toutes les catégories.
     * @return array
     */
    public function getAllCategories()
    {
        return $this->categorieModel->getAll();
    }

    /**
     * Récupère la liste de tous les médicaments.
     * @return array
     */
    public function getAllMedicaments()
    {
        return $this->medicamentModel->getAll();
    }
}

// Initialisation de la connexion et du contrôleur
$database = new Database();
$db = $database->getConnection();
$controller = new ConcernerController($db);

// Gestion des requêtes POST pour l'ajout ou la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
        $controller->update($_POST['IdConcerner'], $_POST);
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
$relations = $controller->index();
$categories = $controller->getAllCategories();
$medicaments = $controller->getAllMedicaments();

ob_start(); // Démarre la mise en tampon de la sortie
?>

<!-- Contenu HTML de la page -->
<div class="col-lg-12">
    <div class="row">
        <div class="col-12">
            <div class="card recent-sales overflow-auto">
                <div class="filter">
                    <a class="icon" data-bs-toggle="modal" data-bs-target="#concernerModal" onclick="openConcernerModal()">
                        <i class="bi bi-plus-circle-fill h4"></i>
                    </a>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $pageTitle ?></h5>

                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] === 'ajout'): ?>
                            <div class="alert alert-success">Relation ajoutée ✅</div>
                        <?php elseif ($_GET['msg'] === 'modif'): ?>
                            <div class="alert alert-info">Relation modifiée ✏️</div>
                        <?php elseif ($_GET['msg'] === 'suppr'): ?>
                            <div class="alert alert-danger">Relation supprimée 🗑️</div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <table class="table datatable text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Catégorie</th>
                                <th>Médicament</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($relations)): ?>
                                <?php foreach ($relations as $r): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($r['IdConcerner']) ?></td>
                                        <td><?= htmlspecialchars($r['IdCategorie']) ?></td>
                                        <td><?= htmlspecialchars($r['IdMedicament']) ?></td>
                                        <td>
                                            <a class="text-info mx-1" href="#" onclick='openConcernerModal(<?= json_encode($r) ?>)'>
                                                <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                            </a>
                                            <a class="text-danger mx-1" href="?delete=<?= htmlspecialchars($r['IdConcerner']) ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                                <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Aucune relation trouvée.</td>
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
<div class="modal fade" id="concernerModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Nouvelle Relation</h5>
                <button type="button" class="btn-close h2 fw-bold" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="concernerForm" method="POST">
                    <input type="hidden" name="IdConcerner" id="IdConcerner">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    
                    <div class="mb-3">
                        <label for="IdCategorieInput" class="form-label">Catégorie</label>
                        <input type="text" class="form-control" id="IdCategorieInput" list="categoriesList" placeholder="Rechercher une catégorie..." required>
                        <datalist id="categoriesList">
                            <?php foreach ($categories as $categorie): ?>
                                <option value="<?= htmlspecialchars($categorie['NomCategorie']) ?>" data-id="<?= htmlspecialchars($categorie['IdCategorie']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <input type="hidden" name="IdCategorie" id="IdCategorie">
                    </div>

                    <div class="mb-3">
                        <label for="IdMedicamentInput" class="form-label">Médicament</label>
                        <input type="text" class="form-control" id="IdMedicamentInput" list="medicamentsList" placeholder="Rechercher un médicament..." required>
                        <datalist id="medicamentsList">
                            <?php foreach ($medicaments as $medicament): ?>
                                <option value="<?= htmlspecialchars($medicament['NomMedicament']) ?>" data-id="<?= htmlspecialchars($medicament['IdMedicament']) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <input type="hidden" name="IdMedicament" id="IdMedicament">
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
     * @param {object|null} r Les données de la relation ou null pour une nouvelle.
     */
    function openConcernerModal(r = null) {
        const isEdit = r !== null;
        document.getElementById('IdConcerner').value = isEdit ? r.IdConcerner : '';
        
        // Gère les champs datalist pour l'édition
        if (isEdit) {
            // Trouver le nom de la catégorie correspondante (ceci est un exemple, vous devrez adapter la logique)
            const categorieOption = document.querySelector(`#categoriesList option[data-id="${r.IdCategorie}"]`);
            document.getElementById('IdCategorieInput').value = categorieOption ? categorieOption.value : '';
            document.getElementById('IdCategorie').value = r.IdCategorie;

            // Trouver le nom du médicament correspondant (idem)
            const medicamentOption = document.querySelector(`#medicamentsList option[data-id="${r.IdMedicament}"]`);
            document.getElementById('IdMedicamentInput').value = medicamentOption ? medicamentOption.value : '';
            document.getElementById('IdMedicament').value = r.IdMedicament;
        } else {
            document.getElementById('IdCategorieInput').value = '';
            document.getElementById('IdMedicamentInput').value = '';
            document.getElementById('IdCategorie').value = '';
            document.getElementById('IdMedicament').value = '';
        }
        
        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Relation" : "Nouvelle Relation";
        
        // Initialise et affiche le modal
        new bootstrap.Modal(document.getElementById('concernerModal')).show();
    }
    
    // Événement pour lier la valeur de l'input caché à l'ID de la datalist
    document.getElementById('IdCategorieInput').addEventListener('input', function() {
        const value = this.value;
        const option = document.querySelector(`#categoriesList option[value="${value}"]`);
        document.getElementById('IdCategorie').value = option ? option.dataset.id : '';
    });

    document.getElementById('IdMedicamentInput').addEventListener('input', function() {
        const value = this.value;
        const option = document.querySelector(`#medicamentsList option[value="${value}"]`);
        document.getElementById('IdMedicament').value = option ? option.dataset.id : '';
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
