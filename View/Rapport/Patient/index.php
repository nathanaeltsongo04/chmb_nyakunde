<?php
/**
 * Fichier index pour la liste des patients.
 * Affiche une liste de tous les patients enregistrés.
 */

session_start();
require_once __DIR__ . '/../../../config/Auth_check.php';
require_once __DIR__ . '/../../../config/Database.php';
// Assurez-vous que le chemin vers votre contrôleur est correct
require_once __DIR__ . '/../../../controller/PatientController.php';

$title = "Liste Patients";
$pageTitle = "Liste des Patients";

// Crée une instance de la classe Database
$database = new Database();
$db = $database->getConnection();
$patientController = new PatientController($db);

// Récupère les paramètres de tri de l'URL, avec des valeurs par défaut
$sort_by = $_GET['sort_by'] ?? 'IdPatient';
$sort_order = $_GET['sort_order'] ?? 'ASC';

// Valide les colonnes de tri autorisées pour éviter les injections SQL
$allowed_columns = ['IdPatient', 'Nom', 'PostNom', 'Prenom', 'DateNaissance', 'Sexe', 'Adresse', 'Telephone'];
if (!in_array($sort_by, $allowed_columns)) {
    $sort_by = 'IdPatient';
}

// Valide l'ordre de tri
if (!in_array(strtoupper($sort_order), ['ASC', 'DESC'])) {
    $sort_order = 'ASC';
}

// Récupère les patients avec les paramètres de tri
// REMARQUE: Vous devez modifier votre méthode index() dans PatientController.php pour accepter ces paramètres.
$patients = $patientController->index($sort_by, $sort_order);

ob_start();
?>

<!-- Contenu HTML de la page -->
<!-- Styles pour l'impression -->
<style>
    @media print {
        /* Masque les éléments non nécessaires pour l'impression */
        .pagetitle, .header, .sidebar, .footer, .print-options-container, .datatable th:last-child, .datatable td:last-child {
            display: none !important;
        }

        /* Ajuste la mise en page de la section principale */
        .main, .card-body {
            margin-top: 0 !important;
            padding: 0 !important;
        }

        /* Styles spécifiques pour l'impression d'un patient unique */
        .patient-card {
            display: block !important;
        }
        .printable-content:not(.patient-card) {
            display: none !important;
        }
    }
</style>

<div class="col-lg-12">
    <div class="card recent-sales overflow-auto">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 print-options-container">
                <h5 class="card-title"><?= $pageTitle ?></h5>
                <!-- Bouton pour ouvrir la modale de tri et d'impression du rapport complet -->
                <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#printModal">
                    <i class="bi bi-printer-fill me-2"></i>Imprimer le rapport
                </button> -->
            </div>

            <!-- Modale pour le tri et l'impression du rapport -->
            <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="printModalLabel">Options d'impression</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Formulaire de tri dans la modale -->
                            <form method="GET" action="">
                                <div class="mb-3">
                                    <label for="sort_by" class="form-label">Trier par :</label>
                                    <select name="sort_by" id="sort_by" class="form-select">
                                        <option value="IdPatient" <?= ($sort_by == 'IdPatient') ? 'selected' : '' ?>>ID Patient</option>
                                        <option value="Nom" <?= ($sort_by == 'Nom') ? 'selected' : '' ?>>Nom</option>
                                        <option value="PostNom" <?= ($sort_by == 'PostNom') ? 'selected' : '' ?>>Post-nom</option>
                                        <option value="Prenom" <?= ($sort_by == 'Prenom') ? 'selected' : '' ?>>Prénom</option>
                                        <option value="DateNaissance" <?= ($sort_by == 'DateNaissance') ? 'selected' : '' ?>>Date de naissance</option>
                                        <option value="Sexe" <?= ($sort_by == 'Sexe') ? 'selected' : '' ?>>Sexe</option>
                                        <option value="Adresse" <?= ($sort_by == 'Adresse') ? 'selected' : '' ?>>Adresse</option>
                                        <option value="Telephone" <?= ($sort_by == 'Telephone') ? 'selected' : '' ?>>Téléphone</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="sort_order" class="form-label">Ordre :</label>
                                    <select name="sort_order" id="sort_order" class="form-select">
                                        <option value="ASC" <?= ($sort_order == 'ASC') ? 'selected' : '' ?>>Ascendant</option>
                                        <option value="DESC" <?= ($sort_order == 'DESC') ? 'selected' : '' ?>>Descendant</option>
                                    </select>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-funnel-fill me-2"></i>Appliquer le tri
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu imprimable principal -->
            <div class="printable-content">
                <table class="table datatable text-center">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Post-nom</th>
                            <th>Prénom</th>
                            <th>Date de naissance</th>
                            <th>Sexe</th>
                            <th>Adresse</th>
                            <th>Téléphone</th>
                            <th class="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($patients)): ?>
                            <?php foreach ($patients as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['IdPatient']) ?></td>
                                    <td><?= htmlspecialchars($p['Nom']) ?></td>
                                    <td><?= htmlspecialchars($p['PostNom']) ?></td>
                                    <td><?= htmlspecialchars($p['Prenom']) ?></td>
                                    <td><?= htmlspecialchars($p['DateNaissance']) ?></td>
                                    <td><?= htmlspecialchars($p['Sexe']) ?></td>
                                    <td><?= htmlspecialchars($p['Adresse']) ?></td>
                                    <td><?= htmlspecialchars($p['Telephone']) ?></td>
                                    <td class="actions">
                                        <!-- Bouton pour imprimer un patient unique -->
                                        <button class="btn btn-sm btn-outline-secondary print-single-button" data-patient-id="<?= htmlspecialchars($p['IdPatient']) ?>">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9">Aucun patient trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour l'impression d'un patient unique -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const printButtons = document.querySelectorAll('.print-single-button');

        printButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const row = e.target.closest('tr');
                if (row) {
                    const originalTable = document.querySelector('.datatable');
                    const clonedTable = originalTable.cloneNode(true);
                    
                    // Créer un clone du thead et de la ligne du patient cliqué
                    const clonedHead = clonedTable.querySelector('thead').cloneNode(true);
                    const clonedRow = row.cloneNode(true);
                    
                    // Supprimer toutes les lignes du tbody du clone sauf celle du patient
                    const clonedTbody = clonedTable.querySelector('tbody');
                    while (clonedTbody.firstChild) {
                        clonedTbody.removeChild(clonedTbody.firstChild);
                    }
                    clonedTbody.appendChild(clonedRow);
                    
                    // Créer un conteneur temporaire pour l'impression
                    const printContainer = document.createElement('div');
                    printContainer.classList.add('patient-card');
                    printContainer.style.display = 'none'; // Le style @media print le rendra visible

                    // Ajouter le titre et le tableau
                    const title = document.createElement('h5');
                    title.textContent = "Détails du Patient";
                    printContainer.appendChild(title);
                    printContainer.appendChild(clonedTable);
                    
                    document.body.appendChild(printContainer);
                    
                    // Déclencher l'impression
                    window.print();
                    
                    // Nettoyer après l'impression
                    document.body.removeChild(printContainer);
                }
            });
        });
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../../templates/layout.php';
?>
