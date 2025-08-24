<?php
/**
 * Page de gestion des infirmiers.
 * Cette page gère l'affichage, l'ajout, la modification et la suppression
 * des infirmiers.
 */

session_start();
// Vérifie les droits d'accès de l'utilisateur
require_once __DIR__ . '/../../config/Auth_check.php';
// Inclut la connexion à la base de données
require_once __DIR__ . '/../../config/Database.php';
// Inclut le contrôleur pour les infirmiers
require_once __DIR__ . '/../../controller/InfirmierController.php';

// Définition des titres de la page
$title = "Infirmiers";
$pageTitle = "Liste des Infirmiers";

// Crée une instance du contrôleur Infirmier
$controller = new InfirmierController();

// Gère les requêtes POST (ajout et modification)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['_method'] ?? 'POST';
    if ($method === 'PUT') {
        // Mise à jour d'un infirmier existant
        $controller->update($_POST['IdInfirmier'], $_POST);
        header("Location: index.php?msg=modif");
        exit;
    } else {
        // Ajout d'un nouvel infirmier
        $matricule = $controller->store($_POST);
        header("Location: index.php?msg=ajout");
        exit;
    }
}

// Gère la suppression via un paramètre GET
if (isset($_GET['delete'])) {
    $controller->destroy($_GET['delete']);
    header("Location: index.php?msg=suppr");
    exit;
}

// Récupère tous les infirmiers pour l'affichage
$infirmiers = $controller->index();
ob_start();
?>

<div class="col-lg-12">
    <div class="card recent-sales overflow-auto">
        <div class="filter">
            <!-- Bouton pour ouvrir le modal d'ajout -->
            <a class="icon" data-bs-toggle="modal" data-bs-target="#infirmierModal" onclick="openInfirmierModal(null)">
                <i class="bi bi-plus-circle-fill h4"></i>
            </a>
        </div>

        <div class="card-body">
            <h5 class="card-title"><?= $pageTitle ?></h5>

            <!-- Affichage des messages de succès -->
            <?php if (isset($_GET['msg'])): ?>
                <?php if ($_GET['msg'] === 'ajout'): ?>
                    <div class="alert alert-success">Infirmier ajouté ✅</div>
                <?php elseif ($_GET['msg'] === 'modif'): ?>
                    <div class="alert alert-info">Infirmier modifié ✏️</div>
                <?php elseif ($_GET['msg'] === 'suppr'): ?>
                    <div class="alert alert-danger">Infirmier supprimé 🗑️</div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Tableau d'affichage des infirmiers -->
            <table class="table datatable text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>PostNom</th>
                        <th>Prénom</th>
                        <th>Téléphone</th>
                        <th>Matricule</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($infirmiers as $inf): ?>
                        <tr>
                            <td><?= $inf['IdInfirmier'] ?></td>
                            <td><?= htmlspecialchars($inf['Nom']) ?></td>
                            <td><?= htmlspecialchars($inf['PostNom']) ?></td>
                            <td><?= htmlspecialchars($inf['Prenom']) ?></td>
                            <td><?= htmlspecialchars($inf['Telephone']) ?></td>
                            <td><?= htmlspecialchars($inf['Matricule']) ?></td>
                            <td>
                                <!-- Bouton de modification -->
                                <a class="text-info mx-1" href="#" onclick='openInfirmierModal(<?= json_encode($inf) ?>)'>
                                    <span class="badge bg-success"><i class="bi bi-pencil-square fa-lg"></i></span>
                                </a>
                                <!-- Bouton de suppression -->
                                <a class="text-danger mx-1" href="?delete=<?= $inf['IdInfirmier'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')">
                                    <span class="badge bg-danger"><i class="bi bi-trash fa-lg"></i></span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<!-- Modal pour l'ajout et la modification -->
<div class="modal fade" id="infirmierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="modalTitle">Nouvel Infirmier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="infirmierForm" method="POST">
                    <input type="hidden" name="IdInfirmier" id="IdInfirmier">
                    <input type="hidden" name="_method" id="_method" value="POST">

                    <div class="mb-3">
                        <input type="text" name="Nom" id="Nom" class="form-control" placeholder="Nom" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="PostNom" id="PostNom" class="form-control" placeholder="PostNom" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="Prenom" id="Prenom" class="form-control" placeholder="Prénom" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="Telephone" id="Telephone" class="form-control" placeholder="Téléphone" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="Email" id="Email" class="form-control" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="Adresse" id="Adresse" class="form-control" placeholder="Adresse">
                    </div>
                    <div class="text-center mt-3">
                        <button id="submitBtn" class="btn btn-secondary w-50" type="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Ouvre le modal d'ajout ou de modification.
     * @param {object|null} inf Les données de l'infirmier à modifier, ou null pour un ajout.
     */
    function openInfirmierModal(inf) {
        const isEdit = inf !== null;
        document.getElementById('IdInfirmier').value = isEdit ? inf.IdInfirmier : '';
        document.getElementById('Nom').value = isEdit ? inf.Nom : '';
        document.getElementById('PostNom').value = isEdit ? inf.PostNom : '';
        document.getElementById('Prenom').value = isEdit ? inf.Prenom : '';
        document.getElementById('Telephone').value = isEdit ? inf.Telephone : '';
        document.getElementById('Email').value = isEdit ? inf.Email : '';
        document.getElementById('Adresse').value = isEdit ? inf.Adresse : '';

        document.getElementById('_method').value = isEdit ? 'PUT' : 'POST';
        document.getElementById('submitBtn').innerText = isEdit ? "Modifier" : "Enregistrer";
        document.getElementById('modalTitle').innerText = isEdit ? "Modifier Infirmier" : "Nouvel Infirmier";

        new bootstrap.Modal(document.getElementById('infirmierModal')).show();
    }

    // Gère la disparition automatique des messages d'alerte
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.classList.add('fade', 'show');
            setTimeout(() => alert.remove(), 500);
        });
    }, 2000);
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
