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
 * Configuration pour le plugin local_afirws
 *
 * @package    local_afirws
 * @copyright  2025 AFI Formation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Intercepter la redirection vers la page de connexion pour les utilisateurs non connectés
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'login') === false) {
    // Si l'utilisateur accède à la page d'accueil et n'est pas connecté
    if (strpos($_SERVER['REQUEST_URI'], '/') !== false && empty($_GET)) {
        // Vérifier si l'utilisateur n'est pas déjà sur la page de landing
        if (strpos($_SERVER['REQUEST_URI'], 'local/afirws/landing.php') === false) {
            // Rediriger vers la page de landing
            header('Location: /local/afirws/landing.php');
            exit;
        }
    }
}
