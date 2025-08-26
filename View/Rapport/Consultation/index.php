<?php
session_start();
require_once __DIR__ . '/../../../config/Auth_check.php';
require_once __DIR__ . '/../../../config/Database.php';

$title = "Consultations";
$pageTitle = "Rapport de Consultations";

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Traitement du filtre patient
$searchPatient = $_GET['patient'] ?? '';

// Requête avec ou sans filtre
$sql = "SELECT c.IdConsulter, c.DateConsultation, c.SignesVitaux, c.Diagnostic,
               p.Nom AS NomPatient, m.Nom AS NomMedecin
        FROM consulter c
        INNER JOIN patient p ON c.IdPatient = p.IdPatient
        INNER JOIN medecin m ON c.IdMedecin = m.IdMedecin ";

if (!empty($searchPatient)) {
    $searchPatient = $db->real_escape_string($searchPatient);
    $sql .= " WHERE p.Nom LIKE '%$searchPatient%' ";
}

$sql .= " ORDER BY c.DateConsultation DESC";

$result = $db->query($sql);
$consultations = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $consultations[] = $row;
    }
}

// Récupération des patients pour le filtre datalist
$patientsList = [];
$resPatients = $db->query("SELECT IdPatient, Nom FROM patient ORDER BY Nom");
if ($resPatients && $resPatients->num_rows > 0) {
    while ($p = $resPatients->fetch_assoc()) {
        $patientsList[] = $p;
    }
}

ob_start();
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= $pageTitle ?></h5>

            <!-- Formulaire de recherche par patient avec icônes -->
            <form method="GET" class="mb-3 row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="patient" class="form-control" placeholder="Rechercher par patient"
                           list="patientOptions" value="<?= htmlspecialchars($searchPatient) ?>">
                    <datalist id="patientOptions">
                        <?php foreach ($patientsList as $p): ?>
                            <option value="<?= htmlspecialchars($p['Nom']) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> <!-- Icône recherche -->
                    </button>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-secondary" onclick="window.print()">
                        <i class="bi bi-printer"></i> <!-- Icône impression -->
                    </button>
                </div>
            </form>

            <!-- Tableau consultable et imprimable -->
            <div id="printableTable">
                <h3 class="text-center">
                    Rapport des Consultations
                    <?php if (!empty($searchPatient)): ?>
                        pour le patient : <?= htmlspecialchars($searchPatient) ?>
                    <?php endif; ?>
                </h3>
                <table class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Médecin</th>
                            <th>Date</th>
                            <th>Signes Vitaux</th>
                            <th>Diagnostic</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($consultations)): ?>
                            <?php foreach ($consultations as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['IdConsulter']) ?></td>
                                    <td><?= htmlspecialchars($c['NomPatient']) ?></td>
                                    <td><?= htmlspecialchars($c['NomMedecin']) ?></td>
                                    <td><?= htmlspecialchars($c['DateConsultation']) ?></td>
                                    <td><?= htmlspecialchars($c['SignesVitaux']) ?></td>
                                    <td><?= htmlspecialchars($c['Diagnostic']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucune consultation trouvée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableTable, #printableTable * {
            visibility: visible;
        }
        #printableTable {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        form, button {
            display: none;
        }
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php
$content = ob_get_clean();
include __DIR__ . '/../../../templates/layout.php';
?>
