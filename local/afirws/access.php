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
 * Contrôle d'accès pour les visiteurs non connectés
 *
 * @package    local_afirws
 * @copyright  2025 AFI Formation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Vérifier si l'utilisateur n'est pas connecté
if (!isloggedin() && !isguestuser()) {
    // Pages autorisées pour les visiteurs
    $allowed_pages = [
        '/local/afirws/landing.php',
        '/login/index.php',
        '/login/forgot_password.php',
        '/login/signup.php',
        '/local/afirws/',
        '/'
    ];
    
    $current_url = $_SERVER['REQUEST_URI'];
    $is_allowed = false;
    
    // Vérifier si l'URL actuelle est dans la liste des pages autorisées
    foreach ($allowed_pages as $allowed_page) {
        if (strpos($current_url, $allowed_page) === 0) {
            $is_allowed = true;
            break;
        }
    }
    
    // Si la page n'est pas autorisée, rediriger vers la page d'accueil
    if (!$is_allowed) {
        // Rediriger vers la page d'accueil qui affichera la page de présentation
        redirect(new moodle_url('/'));
    }
}
