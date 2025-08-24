<?php
// Inclure l'en-tête de votre site
include('../Layout/header.php');
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Rapports Professionnels</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/chmb_nyakunde/View/Statistique/index.php">Accueil</a></li>
                <li class="breadcrumb-item active">Rapports</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <!-- Sélecteurs de Rapports -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 no-print">
                    <div class="col">
                        <a href="#" class="report-card-link" onclick="showReport('patients')">
                            <div class="card bg-light-green">
                                <div class="card-header bg-success">
                                    <i class="bi bi-people"></i>
                                    Rapport des Patients
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Liste complète des patients avec leurs informations.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="report-card-link" onclick="showReport('rendezvous')">
                            <div class="card bg-light-blue">
                                <div class="card-header bg-primary">
                                    <i class="bi bi-calendar-check"></i>
                                    Rapport des Rendez-vous
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Statistiques et détails sur les rendez-vous.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="report-card-link" onclick="showReport('examens')">
                            <div class="card bg-light-red">
                                <div class="card-header bg-danger">
                                    <i class="bi bi-journal-medical"></i>
                                    Rapport des Examens
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Liste et résultats des examens effectués.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col">
                        <a href="#" class="report-card-link" onclick="showReport('paiements')">
                            <div class="card bg-light-yellow">
                                <div class="card-header bg-warning">
                                    <i class="bi bi-cash-stack"></i>
                                    Rapport Financier
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Résumé des paiements par patient et par période.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Conteneur des Rapports -->
                <div id="reportContainer" class="mt-5">
                    <!-- Section des Patients -->
                    <div id="patientsReport" class="report-section active">
                        <div class="card">
                            <div class="card-header bg-success">
                                <i class="bi bi-people"></i>
                                Rapport des Patients
                            </div>
                            <div class="card-body">
                                <p class="text-center text-muted">Chargement des données...</p>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom Complet</th>
                                            <th>Date de Naissance</th>
                                            <th>Téléphone</th>
                                        </tr>
                                    </thead>
                                    <tbody id="patientsTableBody">
                                        <!-- Les données des patients seront insérées ici par JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section des Rendez-vous -->
                    <div id="rendezvousReport" class="report-section">
                        <div class="card">
                            <div class="card-header bg-primary">
                                <i class="bi bi-calendar-check"></i>
                                Rapport des Rendez-vous
                            </div>
                            <div class="card-body">
                                <p class="text-center text-muted">Chargement des données...</p>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date & Heure</th>
                                            <th>Patient</th>
                                            <th>Médecin</th>
                                            <th>Objet</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rendezvousTableBody">
                                        <!-- Les données des rendez-vous seront insérées ici par JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section des Examens -->
                    <div id="examensReport" class="report-section">
                        <div class="card">
                            <div class="card-header bg-danger">
                                <i class="bi bi-journal-medical"></i>
                                Rapport des Examens
                            </div>
                            <div class="card-body">
                                <p class="text-center text-muted">Chargement des données...</p>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom de l'examen</th>
                                            <th>Patient</th>
                                            <th>Date</th>
                                            <th>Résultat</th>
                                        </tr>
                                    </thead>
                                    <tbody id="examensTableBody">
                                        <!-- Les données des examens seront insérées ici par JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Section des Paiements -->
                    <div id="paiementsReport" class="report-section">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <i class="bi bi-cash-stack"></i>
                                Rapport Financier
                            </div>
                            <div class="card-body">
                                <p class="text-center text-muted">Chargement des données...</p>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Patient</th>
                                            <th>Mode</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paiementsTableBody">
                                        <!-- Les données des paiements seront insérées ici par JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Bouton d'impression -->
                <div class="text-center no-print">
                    <button class="btn btn-primary btn-print" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimer le Rapport
                    </button>
                </div>

            </div>
        </div>
    </section>
</main>

<?php
// Inclure les scripts JavaScript de votre site
include('../Layout/footer.php');
?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Simuler des données de la base de données (en pratique, vous utiliserez PHP ou une API pour les récupérer)
        const patients = [
            { id: 1, nom: "Kabasele", postNom: "Kabongo", prenom: "Jean", dateNaissance: "1988-05-15", telephone: "0811234567" },
            { id: 2, nom: "Mali", postNom: "Denga", prenom: "Marie", dateNaissance: "1992-11-23", telephone: "0829876543" },
            { id: 3, nom: "Musafiri", postNom: "Kiza", prenom: "Paul", dateNaissance: "1975-02-10", telephone: "0995551234" },
        ];

        const rendezvous = [
            { id: 1, date: "2024-05-20", heure: "10:00", patient: "Jean Kabasele", medecin: "Dr. Pierre", objet: "Consultation générale" },
            { id: 2, date: "2024-05-21", heure: "14:30", patient: "Marie Mali", medecin: "Dr. Sylvie", objet: "Suivi" },
            { id: 3, date: "2024-05-21", heure: "09:00", patient: "Paul Musafiri", medecin: "Dr. Pierre", objet: "Contrôle" },
        ];

        const examens = [
            { id: 1, nom: "Analyse de sang", patient: "Jean Kabasele", date: "2024-05-20", resultat: "Normal" },
            { id: 2, nom: "Radiographie", patient: "Marie Mali", date: "2024-05-21", resultat: "Fracture du poignet" },
            { id: 3, nom: "Test d'urine", patient: "Paul Musafiri", date: "2024-05-21", resultat: "Positif" },
        ];

        const paiements = [
            { id: 1, date: "2024-05-20", montant: 50.00, patient: "Jean Kabasele", mode: "Espèces" },
            { id: 2, date: "2024-05-21", montant: 120.50, patient: "Marie Mali", mode: "Carte" },
            { id: 3, date: "2024-05-21", montant: 35.00, patient: "Paul Musafiri", mode: "Assurance" },
        ];

        // Fonction pour afficher les données dans les tableaux
        const populateTable = (data, tableBodyId, type) => {
            const tableBody = document.getElementById(tableBodyId);
            if (!tableBody) return;
            tableBody.innerHTML = '';
            data.forEach(item => {
                const row = document.createElement('tr');
                let content = '';
                if (type === 'patients') {
                    content = `
                        <td>${item.id}</td>
                        <td>${item.nom} ${item.postNom} ${item.prenom}</td>
                        <td>${item.dateNaissance}</td>
                        <td>${item.telephone}</td>
                    `;
                } else if (type === 'rendezvous') {
                    content = `
                        <td>${item.id}</td>
                        <td>${item.date} à ${item.heure}</td>
                        <td>${item.patient}</td>
                        <td>${item.medecin}</td>
                        <td>${item.objet}</td>
                    `;
                } else if (type === 'examens') {
                    content = `
                        <td>${item.id}</td>
                        <td>${item.nom}</td>
                        <td>${item.patient}</td>
                        <td>${item.date}</td>
                        <td>${item.resultat}</td>
                    `;
                } else if (type === 'paiements') {
                    content = `
                        <td>${item.id}</td>
                        <td>${item.date}</td>
                        <td>$${item.montant.toFixed(2)}</td>
                        <td>${item.patient}</td>
                        <td>${item.mode}</td>
                    `;
                }
                row.innerHTML = content;
                tableBody.appendChild(row);
            });
        };

        // Remplir les tableaux au chargement de la page
        populateTable(patients, 'patientsTableBody', 'patients');
        populateTable(rendezvous, 'rendezvousTableBody', 'rendezvous');
        populateTable(examens, 'examensTableBody', 'examens');
        populateTable(paiements, 'paiementsTableBody', 'paiements');

        // Afficher le premier rapport par défaut
        showReport('patients');
    });

    // Fonction pour afficher le rapport sélectionné
    function showReport(reportId) {
        const sections = document.querySelectorAll('.report-section');
        sections.forEach(section => {
            section.classList.remove('active');
        });
        document.getElementById(reportId + 'Report').classList.add('active');
    }
</script>

<style>
    /* Styles pour la page de rapports */
    body {
        background-color: #f0f2f5;
    }
    .main {
        padding: 2rem;
    }
    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    .card-header {
        background-color: #4CAF50;
        color: white;
        border-radius: 1rem 1rem 0 0;
        padding: 1.5rem;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }
    .card-header .bi {
        margin-right: 1rem;
    }
    .card-body {
        padding: 2rem;
    }
    .report-section {
        display: none;
    }
    .report-section.active {
        display: block;
    }
    .report-card-link {
        text-decoration: none;
        color: inherit;
    }
    .btn-print {
        background-color: #007bff;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 0.5rem;
        transition: background-color 0.3s;
        margin-top: 2rem;
    }
    .btn-print:hover {
        background-color: #0056b3;
    }
    .text-primary-custom { color: #007bff; }
    .text-success-custom { color: #28a745; }
    .text-danger-custom { color: #dc3545; }
    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.8em;
        border-radius: 1rem;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        .report-section.active, .report-section.active * {
            visibility: visible;
        }
        .report-section.active {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 1rem;
        }
        .btn-print, .card-footer {
            display: none;
        }
        .no-print {
            display: none;
        }
    }
</style>
