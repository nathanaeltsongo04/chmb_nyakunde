<?php
/**
 * Ce script génère un rapport de consultation imprimable pour un patient.
 *
 * Il inclut :
 * - Un formulaire de sélection de patient.
 * - La logique PHP pour récupérer les consultations d'un patient donné.
 * - Le rendu HTML du rapport avec des styles CSS pour l'impression.
 */

// Inclure les fichiers de configuration nécessaires
// Le chemin d'accès a été corrigé pour pointer vers le bon fichier
require_once __DIR__ . '/../../../config/Database.php';

// Initialiser la base de données
$database = new Database();
$db = $database->getConnection();

// Définir la variable IdPatient
$patientId = $_GET['patient_id'] ?? null;

// Gérer la requête si un patient a été sélectionné
if ($patientId) {
    // Requête pour récupérer toutes les consultations pour le patient sélectionné
    $query = "
        SELECT
            c.DateConsultation,
            c.SignesVitaux,
            c.Diagnostic,
            p.Nom AS NomPatient,
            p.PostNom AS PostNomPatient,
            p.Prenom AS PrenomPatient,
            m.Nom AS NomMedecin
        FROM
            Consulter c
        JOIN
            Patient p ON c.IdPatient = p.IdPatient
        JOIN
            Medecin m ON c.IdMedecin = m.IdMedecin
        WHERE
            c.IdPatient = ?
        ORDER BY
            c.DateConsultation DESC
    ";

    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    $result = $stmt->get_result();
    $consultations = $result->fetch_all(MYSQLI_ASSOC);

    // Si des consultations sont trouvées, on récupère les infos du patient
    if (!empty($consultations)) {
        $patientInfo = [
            'NomPatient' => $consultations[0]['NomPatient'],
            'PostNomPatient' => $consultations[0]['PostNomPatient'],
            'PrenomPatient' => $consultations[0]['PrenomPatient']
        ];
    } else {
        $patientInfo = null;
    }
}

// Récupérer la liste de tous les patients pour le menu déroulant
$patientsQuery = "SELECT IdPatient, Nom, PostNom, Prenom FROM Patient ORDER BY Nom";
$patientsResult = $db->query($patientsQuery);
$patients = $patientsResult->fetch_all(MYSQLI_ASSOC);

ob_start(); // Commence la mise en mémoire tampon de la sortie
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Consultation</title>
    <!-- Styles pour l'affichage à l'écran -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 20px;
        }
        .container {
            max-width: 900px;
        }
        .print-button-container {
            text-align: center;
            margin-top: 20px;
        }
        .consultation-card {
            margin-bottom: 20px;
            border-left: 5px solid #007bff;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card-title {
            color: #007bff;
            font-size: 1.25rem;
            border-bottom: 1px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
        }
        /* Styles spécifiques pour l'impression */
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none; /* Cache les éléments qui ne doivent pas être imprimés */
            }
            .container {
                max-width: 100%;
                padding: 0;
            }
            .consultation-card {
                border-left: 5px solid #000;
                box-shadow: none;
                page-break-inside: avoid; /* Empêche une carte de se couper entre deux pages */
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Formulaire de sélection de patient -->
        <div class="no-print mb-4">
            <h1 class="text-center my-4">Rapport de Consultation</h1>
            <form action="" method="GET">
                <div class="input-group">
                    <label class="input-group-text" for="patientSelect">Sélectionner un patient:</label>
                    <select class="form-select" id="patientSelect" name="patient_id" onchange="this.form.submit()">
                        <option value="">-- Choisir un patient --</option>
                        <?php foreach ($patients as $p): ?>
                            <option value="<?= htmlspecialchars($p['IdPatient']) ?>"
                                <?= $p['IdPatient'] == $patientId ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['Nom'] . ' ' . $p['PostNom'] . ' ' . $p['Prenom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <!-- Section du rapport -->
        <?php if ($patientId): ?>
            <div id="report-content">
                <?php if ($patientInfo): ?>
                    <h2 class="text-center my-4">Rapport pour le patient : <?= htmlspecialchars($patientInfo['NomPatient'] . ' ' . $patientInfo['PostNomPatient'] . ' ' . $patientInfo['PrenomPatient']) ?></h2>
                    <div class="print-button-container no-print">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print"></i> Imprimer le rapport
                        </button>
                    </div>
                    <?php if (!empty($consultations)): ?>
                        <?php foreach ($consultations as $c): ?>
                            <div class="consultation-card card">
                                <div class="card-body">
                                    <h5 class="card-title">Consultation du <?= htmlspecialchars($c['DateConsultation']) ?></h5>
                                    <p><span class="info-label">Médecin :</span> <?= htmlspecialchars($c['NomMedecin']) ?></p>
                                    <p><span class="info-label">Signes Vitaux :</span> <?= nl2br(htmlspecialchars($c['SignesVitaux'])) ?></p>
                                    <p><span class="info-label">Diagnostic :</span> <?= nl2br(htmlspecialchars($c['Diagnostic'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning mt-4">
                            Aucune consultation trouvée pour ce patient.
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-danger mt-4">
                        Ce patient n'existe pas ou aucune donnée n'est disponible.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
<?php
// Fin de la mise en mémoire tampon et affichage du contenu
ob_end_flush();
?>
