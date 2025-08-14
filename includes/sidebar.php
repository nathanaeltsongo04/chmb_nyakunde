<!-- ======= Sidebar ======= -->
<?php
if ($_SESSION['fonction'] == 'Administrateur') {
?>
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="statistiques.php">
                    <i class="bi bi-grid"></i>
                    <span>Tableau de Bord</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-heading">Main</li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="documents.php">
                    <i class="bi bi-file-text-fill"></i>
                    <span>Documents</span>
                </a>
            </li><!-- End Document Page Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="commandes.php">
                    <i class="bi bi-hand-index-fill"></i>
                    <span>Commandes</span>
                </a>
            </li><!-- End Commandes Page Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="paiements.php">
                    <i class="bi bi-credit-card-fill"></i>
                    <span>Paiements</span>
                </a>
            </li><!-- End Paiement Page Nav -->
            <!-- <li class="nav-item" >
            <a class="nav-link collapsed" href="paiements.php">
                <i class="bi bi-file-earmark"></i>
                <span>Rapports</span>
            </a>
        </li>End Paiement Page Nav -->

            <li class="nav-heading">Others</li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="services.php">
                    <i class="bi bi-list-task"></i>
                    <span>Services</span>
                </a>
            </li><!-- End Services Page Nav -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="utilisateurs.php">
                    <i class="bi bi-people-fill"></i>
                    <span>Utilisateurs</span>
                </a>
            </li><!-- End Utilisateurs Page Nav -->
        </ul>

    </aside><!-- End Sidebar-->
<?php
} elseif ($_SESSION['fonction'] == 'Bureau I') {
?>
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="statistiques.php">
                    <i class="bi bi-grid"></i>
                    <span>Tableau de Bord</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-heading">Main</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="commandes.php">
                    <i class="bi bi-hand-index-fill"></i>
                    <span>Commandes</span>
                </a>
            </li><!-- End Commandes Page Nav -->
        </ul>

    </aside><!-- End Sidebar-->
<?php
} elseif ($_SESSION['fonction'] == 'Bureau II') {
?>
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href="statistiques.php">
                    <i class="bi bi-grid"></i>
                    <span>Tableau de Bord</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-heading">Main</li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="paiements.php">
                    <i class="bi bi-credit-card-fill"></i>
                    <span>Paiements</span>
                </a>
            </li><!-- End Paiement Page Nav -->
        </ul>

    </aside><!-- End Sidebar-->
<?php
}
?>