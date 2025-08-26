<?php
// Récupération du rôle de l'utilisateur depuis la session
$user_role = $_SESSION['role'] ?? 'guest'; // 'medecin', 'infirmier', 'laborantin', 'caissier', 'admin', 'super_admin'

// Définition des droits
$is_super_admin = ($user_role === 'super_admin');
$is_admin       = ($user_role === 'admin' || $is_super_admin);
$is_medecin     = ($user_role === 'medecin' || $is_admin);
$is_infirmier   = ($user_role === 'infirmier' || $is_admin);
$is_laborantin  = ($user_role === 'laborantin' || $is_admin);
$is_caissier    = ($user_role === 'caissier' || $is_admin);
?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link active" href="/chmb_nyakunde/View/Statistique/index.php">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de Bord</span>
            </a>
        </li>

        <!-- Gestion du Personnel (Admin / Super Admin) -->
        <?php if ($is_admin || $is_medecin): ?>
            <li class="nav-heading text-primary">Gestion du Personnel</li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#personnel-nav">
                    <i class="bi bi-person-circle"></i>
                    <span>Personnel</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="personnel-nav">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Medecin/index.php"><i class="bi bi-person-badge"></i> Médecins</a></li>
                        <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Infirmier/index.php"><i class="bi bi-heart-pulse"></i> Infirmiers</a></li>
                        <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Laborantin/index.php"><i class="bi bi-flask"></i> Laborantins</a></li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>

        <!-- Gestion des Patients -->
        <?php if ($is_medecin || $is_infirmier || $is_admin): ?>
            <li class="nav-heading text-secondary">Gestion des Patients</li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#patients-nav">
                    <i class="bi bi-person-heart"></i>
                    <span>Patients</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="patients-nav">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Patient/index.php"><i class="bi bi-people"></i> Liste des Patients</a></li>
                        <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Rendezvous/index.php"><i class="bi bi-calendar-check"></i> Rendez-vous</a></li>
                        <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Hospitaliser/index.php"><i class="bi bi-hospital"></i> Hospitalisations</a></li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>

        <!-- Actes Médicaux -->
        <?php if ($is_medecin || $is_infirmier || $is_laborantin || $is_admin): ?>
            <li class="nav-heading text-info">Actes Médicaux</li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#actes-nav">
                    <i class="bi bi-journal-check"></i>
                    <span>Consultations & Examens</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="actes-nav">
                    <ul class="nav flex-column ms-3">
                        <?php if ($is_medecin): ?>
                            <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Consulter/index.php"><i class="bi bi-stethoscope"></i> Consultations</a></li>
                        <?php endif; ?>
                        <?php if ($is_medecin || $is_laborantin): ?>
                            <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Examen/index.php"><i class="bi bi-journal-medical"></i> Examens</a></li>
                        <?php endif; ?>
                        <?php if ($is_infirmier): ?>
                            <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Preconsultation/index.php"><i class="bi bi-clipboard2-plus"></i> Préconsultations</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
            <!-- Traitements et Médicaments -->
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#traitements-nav">
                    <i class="bi bi-capsule"></i>
                    <span>Traitements & Médicaments</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="traitements-nav">
                    <ul class="nav flex-column ms-3">
                        <?php if ($is_medecin): ?>
                            <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Traitement/index.php"><i class="bi bi-file-medical"></i> Traitements</a></li>
                        <?php endif; ?>
                        <?php if ($is_infirmier || $is_medecin): ?>
                            <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Medicament/index.php"><i class="bi bi-capsule"></i> Médicaments</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
        <?php endif; ?>

        <!-- Gestion Financière -->
        <?php if ($is_caissier || $is_admin): ?>
            <li class="nav-heading text-success">Gestion Financière</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="/chmb_nyakunde/View/Paiement/index.php">
                    <i class="bi bi-cash-stack"></i> <span>Paiements</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Rapports -->
        <?php if ($is_admin || $is_super_admin || $is_medecin): ?>
            <li class="nav-heading text-info">Rapports</li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" href="#rapports-nav">
                    <i class="bi bi-bar-chart-line"></i> <span>Rapports</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="rapports-nav">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Rapport/Consultation/index.php"><i class="bi bi-file-earmark-bar-graph"></i> Consultations</a></li>
                        <li class="nav-item"><a class="nav-link" href="/chmb_nyakunde/View/Rapport/Patient/index.php"><i class="bi bi-people-fill"></i> Patients</a></li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>

        <!-- Administration -->
        <?php if ($is_super_admin || $is_medecin): ?>
            <li class="nav-heading text-danger">Administration</li>
            <li class="nav-item"><a class="nav-link collapsed" href="/chmb_nyakunde/View/Utilisateur/index.php"><i class="bi bi-people-fill"></i> Utilisateurs</a></li>
            <li class="nav-item"><a class="nav-link collapsed" href="/chmb_nyakunde/View/Chambre/index.php"><i class="bi bi-hospital"></i> Chambres</a></li>
            <!-- <li class="nav-item"><a class="nav-link collapsed" href="/chmb_nyakunde/View/Categorie/index.php"><i class="bi bi-tags"></i> Catégories</a></li> -->
        <?php endif; ?>

    </ul>
</aside>
<!-- End Sidebar -->
