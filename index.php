<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Centre Hospitalier Nyakunde</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="./assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="./assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="./assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="./assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="./assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="./assets/css/style.css" rel="stylesheet">
    <style>
        .hero {
            background: url('assets/img/hospital_banner.jpg') center/cover no-repeat;
            height: 80vh;
            display: flex;
            align-items: center;
            color: white;
            text-shadow: 1px 1px 3px #000;
        }
        .feature-card img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container px-5">
            <a class="navbar-brand" href="#">Centre Hospitalier Nyakunde</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto text-bold">
                    <li class="nav-item"><a class="nav-link active" href="#">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#promo">Promo</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero text-center">
    <div class="overlay"></div>
    <div class="container">
        <h1 class="display-4 fw-bolder">Bienvenue au Centre Hospitalier Nyakunde</h1>
        <p class="lead mb-4">Des soins de qualité pour tous, avec des spécialistes à votre service.</p>
        <a href="./View/Medecin/index.php" class="btn btn-light btn-lg me-2">Nos Médecins</a>
        <a href="./View/Examen/index.php" class="btn btn-outline-light btn-lg">Nos Examens</a>
    </div>
</header>

<style>
.hero {
    position: relative;
    height: 80vh; /* ajuste la hauteur selon ton besoin */
    background: url('assets/img/hopital.jpg') center center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-shadow: 1px 1px 3px #000;
}

.hero .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4); /* teinte sombre pour lisibilité du texte */
}

.hero .container {
    position: relative; /* pour que le texte soit au-dessus de l'overlay */
    z-index: 1;
}
</style>


    <!-- Services Section -->
    <section class="py-5" id="services">
        <div class="container">
            <h2 class="fw-bolder text-center mb-5">Nos Services</h2>
            <div class="row g-4 text-center">
                <div class="col-lg-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <img src="assets/img/consultation.jpg" class="card-img-top" alt="Consultation">
                        <div class="card-body">
                            <h5 class="card-title">Consultations</h5>
                            <p class="card-text">Des médecins spécialistes pour vous accompagner dans vos soins.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <img src="assets/img/pharmacie.jpg" class="card-img-top" alt="Pharmacie">
                        <div class="card-body">
                            <h5 class="card-title">Pharmacie</h5>
                            <p class="card-text">Un large choix de médicaments et produits de santé disponibles sur place.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card feature-card h-100 border-0 shadow-sm">
                        <img src="assets/img/examen.jpg" class="card-img-top" alt="Examens">
                        <div class="card-body">
                            <h5 class="card-title">Examens & Analyses</h5>
                            <p class="card-text">Analyses médicales complètes pour un diagnostic précis et rapide.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Section -->
    <section class="bg-light py-5" id="promo">
        <div class="container text-center">
            <h2 class="fw-bolder mb-3">Consultation Spéciale</h2>
            <p class="lead mb-4">Profitez de notre programme de consultation gratuite pour les enfants de moins de 12 ans.</p>
            <a href="#contact" class="btn btn-primary btn-lg">Prendre Rendez-vous</a>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <h2 class="fw-bolder text-center mb-5">Témoignages</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <p>“Merci au personnel du centre hospitalier pour leur professionnalisme et leur gentillesse. Je me suis senti pris en charge dès mon arrivée.”</p>
                            <div class="small text-muted">- Jean M., Patient</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <p>“Des médecins compétents et une équipe à l’écoute. Mon examen a été rapide et précis.”</p>
                            <div class="small text-muted">- Amina K., Patient</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="bg-primary text-white py-5" id="contact">
        <div class="container">
            <h2 class="fw-bolder text-center mb-4">Contactez-nous</h2>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Nom complet" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="Votre message" required></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-light btn-lg">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container text-center text-white">
            &copy; 2025 Centre Hospitalier Nyakunde. Tous droits réservés.
        </div>
    </footer>

    <!-- Vendor JS Files -->
    <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
