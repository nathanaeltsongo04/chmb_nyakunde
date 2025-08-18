<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Définir la racine du projet pour corriger tous les chemins
    $baseUrl = '/chmb_nyakunde'; // <-- adapte selon le nom de ton dossier dans htdocs
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?= $title ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= $baseUrl ?>/assets/img/armoirie.png" rel="icon">
    <link href="<?= $baseUrl ?>/assets/img/armoirie.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= $baseUrl ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?= $baseUrl ?>/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?= $baseUrl ?>/assets/css/style.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="<?= $baseUrl ?>/node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

</head>

<body>
    <!-- ======= Header ======= -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- ======= Sidebar ======= -->
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Tableau de bord</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= $baseUrl ?>/index.php"><?= $pageTitle ?></a>
                    </li>
                    <li class="breadcrumb-item active">Tableau de bord</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <?= $content ?>
            </div>
        </section>
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="<?= $baseUrl ?>/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="<?= $baseUrl ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $baseUrl ?>/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="<?= $baseUrl ?>/assets/vendor/echarts/echarts.min.js"></script>
    <script src="<?= $baseUrl ?>/assets/vendor/quill/quill.min.js"></script>
    <script src="<?= $baseUrl ?>/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="<?= $baseUrl ?>/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="<?= $baseUrl ?>/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="<?= $baseUrl ?>/assets/js/main.js"></script>
</body>
</html>
