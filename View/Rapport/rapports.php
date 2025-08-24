<?php
/**
 * Fichier du tableau de bord des rapports.
 * Il sert de page d'accueil pour la section des rapports, affichant
 * des options pour générer différents types de rapports.
 */
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';

$title = "Tableau de Bord des Rapports";
$pageTitle = "Générer un Rapport";

ob_start();
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Sélectionnez le type de rapport à générer</h5>
                </div>
                <div class="card-body p-4">
                    <div class="list-group list-group-flush">
                        <!-- Lien vers le rapport des consultations -->
                        <a href="Consultation/index.php" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-file-earmark-text-fill me-2 text-info"></i>
                                <span class="fw-bold">Rapport des consultations</span>
                            </div>
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        <!-- Lien vers le rapport des patients -->
                        <a href="Patient/index.php" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-person-lines-fill me-2 text-info"></i>
                                <span class="fw-bold">Rapport des patients</span>
                            </div>
                            <i class="bi bi-arrow-right"></i>
                        </a>

                        <!-- Autres rapports à venir (désactivés) -->
                        <a href="#" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center disabled">
                            <div>
                                <i class="bi bi-clipboard-data-fill me-2 text-info"></i>
                                <span class="fw-bold">Rapport des examens de laboratoire</span>
                            </div>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                        
                        <a href="#" class="list-group-item list-group-item-action py-3 d-flex justify-content-between align-items-center disabled">
                            <div>
                                <i class="bi bi-currency-dollar me-2 text-info"></i>
                                <span class="fw-bold">Rapport financier</span>
                            </div>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>
