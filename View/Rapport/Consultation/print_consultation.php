<?php
// Inclure les fichiers nécessaires
include_once '../../../../../Model/Consultation.php';
include_once '../../../../../Model/Database.php';

// Vérifier si l'ID de la consultation est présent dans l'URL
if (!isset($_GET['IdConsultation'])) {
    die("ID de consultation manquant.");
}

$consultationId = $_GET['IdConsultation'];

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Initialisation de l'objet Consultation
$consultation = new Consultation($db);
$consultationDetails = $consultation->getConsultationById($consultationId);

// Vérifier si la consultation a été trouvée
if (!$consultationDetails) {
    die("Consultation non trouvée.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression de la Consultation</title>
    <!-- Inclure Bootstrap CSS pour le style de base -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .header-print {
            text-align: center;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        h1, h2, h3 {
            color: #333;
        }
        .info-block {
            margin-bottom: 15px;
        }
        .info-block label {
            font-weight: bold;
        }
        /* Style pour l'impression */
        @media print {
            body {
                background-color: white;
            }
            .container {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-print">
            <h3>Rapport de Consultation</h3>
            <h4>Clinique CHMB Nyakunde</h4>
        </div>
        <div class="row mb-4">
            <div class="col">
                <p class="info-block"><label>Date de la Consultation:</label> <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($consultationDetails['DateConsultation']))); ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 info-block">
                <h4>Informations du Patient</h4>
                <p><label>Nom Complet:</label> <?php echo htmlspecialchars($consultationDetails['NomPatient'] . " " . $consultationDetails['PostNomPatient'] . " " . $consultationDetails['PrenomPatient']); ?></p>
                <p><label>Date de naissance:</label> <?php echo htmlspecialchars(date('d/m/Y', strtotime($consultationDetails['DateNaissancePatient']))); ?></p>
                <p><label>Adresse:</label> <?php echo htmlspecialchars($consultationDetails['AdressePatient']); ?></p>
            </div>
            <div class="col-md-6 info-block">
                <h4>Informations du Médecin</h4>
                <p><label>Médecin Traitant:</label> <?php echo htmlspecialchars($consultationDetails['NomMedecin'] . " " . $consultationDetails['PostNomMedecin'] . " " . $consultationDetails['PrenomMedecin']); ?></p>
            </div>
        </div>
        
        <hr>

        <div class="row mt-4">
            <div class="col-12 info-block">
                <h4>Motif de la Consultation</h4>
                <p><?php echo htmlspecialchars($consultationDetails['Motif']); ?></p>
            </div>
            <div class="col-12 info-block">
                <h4>Diagnostic</h4>
                <p><?php echo htmlspecialchars($consultationDetails['Diagnostic']); ?></p>
            </div>
            <div class="col-12 info-block">
                <h4>Traitement</h4>
                <p><?php echo htmlspecialchars($consultationDetails['Traitement']); ?></p>
            </div>
            <div class="col-12 info-block">
                <h4>Observations</h4>
                <p><?php echo htmlspecialchars($consultationDetails['Observations']); ?></p>
            </div>
        </div>
    </div>

    <!-- Script pour l'impression automatique -->
    <script>
        window.onload = function() {
            window.print();
            // Optionnel : fermer la fenêtre après l'impression ou l'annulation
            // setTimeout(() => { window.close(); }, 100);
        };
    </script>
</body>
</html>
