<?php
/**
 * Fichier index pour le rapport de consultation.
 * Affiche une liste de toutes les consultations enregistrées avec une recherche dynamique.
 */

session_start();
require_once __DIR__ . '/../../../config/Auth_check.php';
require_once __DIR__ . '/../../../config/Database.php';
require_once __DIR__ . '/../../../controller/ConsultationController.php';

$title = "Rapport de Consultation";
$pageTitle = "Rapport de Consultation";

// Crée une instance de la classe Database
$database = new Database();
$db = $database->getConnection();
$consultationController = new ConsultationController($db);

// Récupère la liste complète des consultations. La recherche est gérée par JavaScript.
// NOTE: Assurez-vous que votre méthode index() dans ConsultationController retourne un tableau de consultations.
$consultations = $consultationController->index();

ob_start();
?>

<!-- Contenu HTML de la page -->
<!-- Styles pour l'impression -->
<style>
    @media print {
        /* Masque les éléments non nécessaires pour l'impression */
        .pagetitle, .header, .sidebar, .footer, .print-options-container, .datatable th:last-child, .datatable td:last-child, .form-control {
            display: none !important;
        }

        /* Ajuste la mise en page de la section principale */
        .main, .card-body {
            margin-top: 0 !important;
            padding: 0 !important;
        }
        
        /* Styles pour le mode portrait */
        @page {
            size: auto;
            margin: 10mm;
        }

        /* Styles spécifiques pour l'impression d'une fiche de consultation */
        .printable-card-container {
            display: block !important;
            page-break-after: always; /* Force un saut de page après chaque fiche */
        }
        .printable-content {
            display: none;
        }
        
        .printable-card {
            border: 1px solid #dee2e6;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    }
</style>

<div class="col-lg-12">
    <div class="card recent-sales overflow-auto">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 print-options-container">
                <h5 class="card-title"><?= $pageTitle ?></h5>
                <!-- Bouton pour ouvrir la modale d'impression du rapport complet -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#printModal">
                    <i class="bi bi-printer-fill me-2"></i>Imprimer le rapport
                </button>
            </div>
            
            <!-- Champ de recherche dynamique -->
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par patient, motif ou diagnostique...">
            </div>

            <!-- Modale pour l'impression du rapport complet -->
            <div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="printModalLabel">Options d'impression</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>Le rapport sera imprimé tel qu'il apparaît à l'écran, avec les filtres actuellement appliqués.</p>
                            <button type="button" class="btn btn-primary mt-3" onclick="window.print()">
                                <i class="bi bi-printer-fill me-2"></i>Confirmer l'impression
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu imprimable principal -->
            <div class="printable-content">
                <table class="table datatable text-center">
                    <thead>
                        <tr>
                            <th>ID Consultation</th>
                            <th>ID Patient</th>
                            <th>Date</th>
                            <th>Motif</th>
                            <th>Diagnostique</th>
                            <th class="actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="consultationTableBody">
                        <!-- Le contenu sera généré dynamiquement par JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Conteneur pour les fiches de patients imprimables -->
<div class="printable-card-container" style="display: none;"></div>

<!-- Script pour la recherche et l'impression dynamiques -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Récupère les données des consultations encodées en JSON depuis le PHP
        const consultationsData = <?= json_encode($consultations) ?>;
        let currentConsultations = [...consultationsData];

        const tableBody = document.getElementById('consultationTableBody');
        const searchInput = document.getElementById('searchInput');
        const printableCardContainer = document.querySelector('.printable-card-container');

        // Fonction pour rendre le tableau
        const renderTable = (data) => {
            tableBody.innerHTML = '';
            if (data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6">Aucune consultation trouvée.</td></tr>';
                return;
            }
            data.forEach(c => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${c.IdConsultation}</td>
                    <td>${c.IdPatient}</td>
                    <td>${c.DateConsultation}</td>
                    <td>${c.Motif}</td>
                    <td>${c.Diagnostique}</td>
                    <td class="actions">
                        <button class="btn btn-sm btn-outline-secondary print-single-button" data-consultation-id="${c.IdConsultation}">
                            <i class="bi bi-printer"></i> Détails & Imprimer
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        };

        // Gère la recherche
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            currentConsultations = consultationsData.filter(c =>
                c.IdPatient.toString().toLowerCase().includes(searchTerm) || // Permet de rechercher par ID Patient
                c.Motif.toLowerCase().includes(searchTerm) ||
                c.Diagnostique.toLowerCase().includes(searchTerm)
            );
            renderTable(currentConsultations);
        });

        // Gère l'impression d'une seule consultation
        document.addEventListener('click', (e) => {
            if (e.target.closest('.print-single-button')) {
                const consultationId = e.target.closest('button').getAttribute('data-consultation-id');
                const consultation = consultationsData.find(c => c.IdConsultation == consultationId);
                
                if (consultation) {
                    // Crée une carte de consultation pour l'impression
                    const printCard = document.createElement('div');
                    printCard.classList.add('printable-card');
                    printCard.innerHTML = `
                        <h4 class="text-center mb-4">Fiche de Consultation</h4>
                        <div class="row">
                            <div class="col-sm-6 mb-2"><b>ID Consultation:</b> ${consultation.IdConsultation}</div>
                            <div class="col-sm-6 mb-2"><b>ID Patient:</b> ${consultation.IdPatient}</div>
                            <div class="col-sm-6 mb-2"><b>Date de Consultation:</b> ${consultation.DateConsultation}</div>
                            <div class="col-sm-6 mb-2"><b>Motif:</b> ${consultation.Motif}</div>
                            <div class="col-sm-12 mb-2"><b>Diagnostique:</b> ${consultation.Diagnostique}</div>
                        </div>
                    `;
                    
                    // Ajoute la carte au conteneur d'impression et déclenche l'impression
                    printableCardContainer.innerHTML = '';
                    printableCardContainer.appendChild(printCard);
                    window.print();
                }
            }
        });

        // Rend le tableau initial
        renderTable(consultationsData);
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../../templates/layout.php';
?>
