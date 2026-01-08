<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Page de redirection vers la landing page pour les visiteurs
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/outputlib.php');

// Si l'utilisateur est connecté, rediriger vers la page d'accueil normale
if (isloggedin() && !isguestuser()) {
    redirect(new moodle_url('/'));
    exit;
}

// Sinon, afficher la page de présentation
echo '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Association de Formation des Imprimeurs Rotativistes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .btn-primary-custom {
            background-color: #007bff;
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            color: white;
            margin: 0 10px;
        }
        .btn-primary-custom:hover {
            background-color: #0056b3;
            color: white;
        }
        .btn-secondary-custom {
            background-color: #6c757d;
            border: none;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            color: white;
            margin: 0 10px;
        }
        .btn-secondary-custom:hover {
            background-color: #545b62;
            color: white;
        }
        .features-section {
            padding: 80px 0;
            background-color: #f8f9fa;
        }
        .feature-box {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        .feature-box:hover {
            transform: translateY(-5px);
        }
        .feature-box i {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
        }
        .catalog-section {
            padding: 80px 0;
            background-color: white;
        }
        .course-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .course-card:hover {
            transform: translateY(-5px);
        }
        .course-card h5 {
            color: #667eea;
            font-weight: bold;
        }
        .access-denied {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap"></i> AFI Formation
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#catalogue">Catalogue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login/index.php">Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1>Association de Formation des Imprimeurs Rotativistes</h1>
            <p>Découvrez nos formations professionnelles de qualité pour les métiers de l\'imprimerie</p>
            <div>
                <a href="/login/index.php" class="btn-primary-custom">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </a>
                <a href="#catalogue" class="btn-secondary-custom">
                    <i class="fas fa-book"></i> Voir le catalogue
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="fas fa-graduation-cap"></i>
                        <h3>Formations Qualifiantes</h3>
                        <p>Des programmes adaptés aux besoins de l\'industrie de l\'imprimerie</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="fas fa-users"></i>
                        <h3>Experts Reconnus</h3>
                        <p>Des formateurs expérimentés et passionnés par leur métier</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <i class="fas fa-certificate"></i>
                        <h3>Certifications</h3>
                        <p>Des diplômes reconnus dans le secteur professionnel</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Catalog Section -->
    <div id="catalogue" class="catalog-section">
        <div class="container">
            <h2 class="text-center mb-5">Notre Catalogue de Formations</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="course-card">
                        <h5><i class="fas fa-print"></i> Techniques d\'Impression</h5>
                        <p>Maîtrise des techniques modernes d\'impression rotative</p>
                        <span class="badge bg-primary">3 mois</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="course-card">
                        <h5><i class="fas fa-cogs"></i> Maintenance des Équipements</h5>
                        <p>Entretien et dépannage des machines d\'impression</p>
                        <span class="badge bg-primary">2 mois</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="course-card">
                        <h5><i class="fas fa-palette"></i> Pré-presse et PAO</h5>
                        <p>Préparation des fichiers et gestion de la couleur</p>
                        <span class="badge bg-primary">1 mois</span>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <div class="access-denied">
                    <h5><i class="fas fa-lock"></i> Accès réservé</h5>
                    <p>Pour accéder à nos formations complètes et aux cours disponibles, veuillez vous connecter.</p>
                    <a href="/login/index.php" class="btn-primary-custom">
                        <i class="fas fa-sign-in-alt"></i> Se connecter pour voir les formations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p>&copy; 2025 Association de Formation des Imprimeurs Rotativistes. Tous droits réservés.</p>
            <p>
                <a href="/login/index.php" class="text-white me-3">Connexion</a>
                <a href="#contact" class="text-white">Contact</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>';
