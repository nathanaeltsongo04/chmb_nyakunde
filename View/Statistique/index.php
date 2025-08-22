<?php
session_start();
require_once __DIR__ . '/../../config/Auth_check.php';
require_once __DIR__ . '/../../controller/DashboardController.php';

$title = "Statistiques";
$pageTitle = "Dashboard";

$controller = new DashboardController();
$stats = $controller->index();

ob_start();
?>

<div class="col-lg-12">
    <div class="row">
        <!-- Patients -->
        <div class="col-xxl-3 col-md-3">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Patients</h5>
                    <h6><?= $stats['patient'] ?></h6>
                </div>
            </div>
        </div>

        <!-- Médecins -->
        <div class="col-xxl-3 col-md-3">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Médecins</h5>
                    <h6><?= $stats['medecin'] ?></h6>
                </div>
            </div>
        </div>

        <!-- Infirmiers -->
        <div class="col-xxl-3 col-md-3">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Infirmiers</h5>
                    <h6><?= $stats['infirmier'] ?></h6>
                </div>
            </div>
        </div>

        <!-- Laborantins -->
        <div class="col-xxl-3 col-md-3">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Laborantins</h5>
                    <h6><?= $stats['laborantin'] ?></h6>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../templates/layout.php';
?>