<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link active" href="/chmb_nyakunde/View/Statistique/index.php">
                <i class="bi bi-speedometer2"></i>
                <span>Tableau de Bord</span>
                <span class="badge bg-primary ms-auto">3</span>
            </a>
        </li>

        <li class="nav-heading text-primary">Gestion Hospitalière</li>

        <!-- Ressources -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#resources-nav" role="button" aria-expanded="false" aria-controls="resources-nav">
                <i class="bi bi-people"></i>
                <span>Ressources</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="resources-nav">
                <ul class="nav flex-column ms-3">
                    <!-- Médecins -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#medecins-nav" role="button" aria-expanded="false">
                            <i class="bi bi-person-badge"></i> Médecins
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="medecins-nav">
                            <ul class="nav flex-column ms-3">
                                <li class="nav-item">
                                    <a class="nav-link" href="/chmb_nyakunde/View/Medecin/index.php">
                                        <i class="bi bi-person"></i> Tous les Médecins
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/chmb_nyakunde/View/Medecin/specialites.php">
                                        <i class="bi bi-bookmark-star"></i> Spécialités
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Patients -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#patients-nav" role="button" aria-expanded="false">
                            <i class="bi bi-person-heart"></i> Patients
                            <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse" id="patients-nav">
                            <ul class="nav flex-column ms-3">
                                <li class="nav-item">
                                    <a class="nav-link" href="/chmb_nyakunde/View/Patient/index.php">
                                        <i class="bi bi-people"></i> Liste des Patients
                                        <span class="badge bg-danger ms-auto">12</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="/chmb_nyakunde/View/Patient/rendezvous.php">
                                        <i class="bi bi-calendar-check"></i> Rendez-vous
                                        <span class="badge bg-warning ms-auto">5</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <!-- Examens -->
                    <li class="nav-item">
                        <a class="nav-link" href="/chmb_nyakunde/View/Examen/index.php">
                            <i class="bi bi-journal-medical"></i> Examens
                        </a>
                    </li>

                    <!-- Médicaments -->
                    <li class="nav-item">
                        <a class="nav-link" href="/chmb_nyakunde/View/Medicament/index.php">
                            <i class="bi bi-capsule"></i> Médicaments
                        </a>
                    </li>

                    <!-- Chambres -->
                    <li class="nav-item">
                        <a class="nav-link" href="/chmb_nyakunde/View/Chambre/index.php">
                            <i class="bi bi-hospital"></i> Chambres
                        </a>
                    </li>

                    <!-- Catégories -->
                    <li class="nav-item">
                        <a class="nav-link" href="/chmb_nyakunde/View/Categorie/index.php">
                            <i class="bi bi-tags"></i> Catégories
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-heading text-success">Administration</li>

        <!-- Utilisateurs -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="/chmb_nyakunde/View/Utilisateur/index.php">
                <i class="bi bi-people-fill"></i>
                <span>Utilisateurs</span>
            </a>
        </li>

        <!-- Paramètres -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="/chmb_nyakunde/parametres.php">
                <i class="bi bi-gear"></i>
                <span>Paramètres</span>
            </a>
        </li>

        <li class="nav-heading text-warning">Notifications</li>

        <!-- Messages -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="/chmb_nyakunde/messages.php">
                <i class="bi bi-chat-dots"></i>
                <span>Messages</span>
                <span class="badge bg-warning ms-auto">4</span>
            </a>
        </li>

        <!-- Rapports -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="/chmb_nyakunde/rapports.php">
                <i class="bi bi-file-earmark-text"></i>
                <span>Rapports</span>
                <span class="badge bg-info ms-auto">2</span>
            </a>
        </li>

    </ul>
</aside>
<!-- End Sidebar -->
