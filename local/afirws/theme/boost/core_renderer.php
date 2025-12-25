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
 * Renderer personnalisé pour le thème Boost
 *
 * @package    local_afirws
 * @copyright  2025 AFI Formation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class theme_boost_core_renderer extends \theme_boost\output\core_renderer {
    
    /**
     * Surcharger la méthode principale pour afficher la page de présentation
     */
    public function header() {
        global $PAGE, $USER, $CFG;
        
        // Si l'utilisateur n'est pas connecté et qu'on est sur la page d'accueil
        if (!isloggedin() && !isguestuser() && $PAGE->pagetype === 'site-index') {
            // Afficher la page de présentation personnalisée
            return $this->custom_landing_page();
        }
        
        // Sinon, afficher l'en-tête standard
        return parent::header();
    }
    
    /**
     * Page de présentation personnalisée
     */
    private function custom_landing_page() {
        global $OUTPUT, $PAGE;
        
        $PAGE->set_pagelayout('frontpage');
        $PAGE->set_title('Association de Formation des Imprimeurs Rotativistes');
        $PAGE->set_heading('Association de Formation des Imprimeurs Rotativistes');
        
        $html = '';
        
        // En-tête HTML
        $html .= '<!DOCTYPE html>';
        $html .= '<html>';
        $html .= '<head>';
        $html .= '<meta charset="utf-8">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $html .= '<title>Association de Formation des Imprimeurs Rotativistes</title>';
        $html .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">';
        $html .= '<style>
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
            }
            .btn-primary-custom:hover {
                background-color: #0056b3;
                color: white;
            }
            .features-section {
                padding: 80px 0;
            }
            .feature-box {
                text-align: center;
                padding: 30px;
            }
            .feature-box i {
                font-size: 3rem;
                color: #667eea;
                margin-bottom: 20px;
            }
        </style>';
        $html .= '</head>';
        $html .= '<body>';
        
        // Section Hero
        $html .= '<div class="hero-section">';
        $html .= '<div class="container">';
        $html .= '<h1>Association de Formation des Imprimeurs Rotativistes</h1>';
        $html .= '<p>Découvrez nos formations professionnelles de qualité pour les métiers de l\'imprimerie</p>';
        $html .= '<a href="/login/index.php" class="btn-primary-custom">Se connecter</a>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Section Features
        $html .= '<div class="features-section">';
        $html .= '<div class="container">';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-4">';
        $html .= '<div class="feature-box">';
        $html .= '<i class="fas fa-graduation-cap"></i>';
        $html .= '<h3>Formations Qualifiantes</h3>';
        $html .= '<p>Des programmes adaptés aux besoins de l\'industrie de l\'imprimerie</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-md-4">';
        $html .= '<div class="feature-box">';
        $html .= '<i class="fas fa-users"></i>';
        $html .= '<h3>Experts Reconnus</h3>';
        $html .= '<p>Des formateurs expérimentés et passionnés par leur métier</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="col-md-4">';
        $html .= '<div class="feature-box">';
        $html .= '<i class="fas fa-certificate"></i>';
        $html .= '<h3>Certifications</h3>';
        $html .= '<p>Des diplômes reconnus dans le secteur professionnel</p>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        // Footer
        $html .= '<footer class="bg-dark text-white text-center py-4">';
        $html .= '<div class="container">';
        $html .= '<p>&copy; 2025 Association de Formation des Imprimeurs Rotativistes. Tous droits réservés.</p>';
        $html .= '</div>';
        $html .= '</footer>';
        
        $html .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>';
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }
}
